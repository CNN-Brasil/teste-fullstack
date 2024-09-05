<?php

namespace LoteriasPlugin;

class LoteriasApi
{
    public function fetch_lottery_api_result($lottery, $contest)
    {
        $api_url = 'https://loteriascaixa-api.herokuapp.com/api/' . $lottery;

        if ($contest === 'ultimo') {
            $api_url .= '/latest';
        } else {
            $api_url .= '/' . $contest;
        }

        $cache_key = 'loteria_' . $lottery . '_' . $contest;
        $cached_result = get_transient($cache_key);
        if ($cached_result) {
            return json_decode($cached_result);
        }

        $response = wp_remote_get($api_url, ['timeout' => 20]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        set_transient($cache_key, $body, HOUR_IN_SECONDS);
        return json_decode($body);
    }
}
