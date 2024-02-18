<?php
class Loterias_API
{
  private $api_url = 'https://loteriascaixa-api.herokuapp.com/api';

  public function get_results($loteria, $concurso)
  {

    $cache_key = 'loterias_' . $loteria . '_' . $concurso;


    $results = wp_cache_get($cache_key, 'loterias');

    if (false === $results) {


      $response = wp_remote_get($this->api_url . '/' . $loteria . '/' . $concurso);

      if (is_wp_error($response)) {
        return false;
      }

      $body = wp_remote_retrieve_body($response);
      $results = json_decode($body, true);


      wp_cache_set($cache_key, $results, 'loterias', 86400);
    }

    return $results;
  }
}
