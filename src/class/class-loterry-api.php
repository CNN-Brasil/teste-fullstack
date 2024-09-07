<?php

class LotteryAPI
{

  private $api_url = 'https://loteriascaixa-api.herokuapp.com/api/';

  // Method to search the result
  public function get_concurso($loteria, $concurso)
  {
    // Verify the cache before search at API
    $cache_key = 'loteria_' . $loteria . '_concurso_' . $concurso;
    $cached_data = get_transient($cache_key);

    if ($cached_data) {
      return $cached_data;
    }

    $response = wp_remote_get($this->api_url . $loteria . '/' . $concurso);
    if (is_wp_error($response)) {
      return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Save cache for 1 hour
    set_transient($cache_key, $data, HOUR_IN_SECONDS);

    return $data;
  }
}
