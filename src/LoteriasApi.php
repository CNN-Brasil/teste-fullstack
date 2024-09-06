<?php

namespace LoteriasPlugin;

class LoteriasApi
{
    /**
     * Fetch the lottery result from the API based on the lottery name and contest number.
     *
     * This method checks if the result is cached using a WordPress transient. If not, it makes a remote
     * request to the Loterias Caixa API to fetch the result. The API URL is constructed dynamically based
     * on whether the contest number is 'ultimo' (latest) or a specific contest number.
     *
     * @param string $lottery The name of the lottery.
     * @param string|int $contest The contest number or 'ultimo' to fetch the latest result.
     * @return object|false Returns the lottery result as an object if successful, or false if the request fails.
     */
    public function fetch_lottery_api_result($lottery, $contest)
    {
        $cache_key = 'loteria_' . $lottery . '_' . $contest;

        $cached_result = get_transient($cache_key);
        if ($cached_result) {
            return json_decode($cached_result);
        }

        $api_url = 'https://loteriascaixa-api.herokuapp.com/api/' . $lottery;

        if ($contest === 'ultimo') {
            $api_url .= '/latest';
        } else {
            $api_url .= '/' . $contest;
        }

        if (function_exists('vip_safe_wp_remote_get')) {
            $response = vip_safe_wp_remote_get($api_url, ['timeout' => 3]);
        } else {
            $response = wp_remote_get($api_url, ['timeout' => 3]);
        }

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $api_result = json_decode($body);

        if ($contest === 'ultimo' && isset($api_result->concurso)) {
            $contest = $api_result->concurso;
            $cache_key = 'loteria_' . $lottery . '_' . $contest;
        }

        set_transient($cache_key, $body, HOUR_IN_SECONDS);

        return $api_result;
    }
}
