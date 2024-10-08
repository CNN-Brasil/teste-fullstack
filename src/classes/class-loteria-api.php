<?php

class Loteria_API
{
  const API_URL_BASE = 'https://loteriascaixa-api.herokuapp.com/api/';

  /**
   * Obtém o resultado de um concurso de uma loteria.
   *
   * @param string $loteria Nome da loteria (e.g., megasena, quina).
   * @param string|int $concurso Número do concurso ou "latest" para o mais recente.
   *
   * @return array|false Retorna os dados do resultado ou false se houver erro.
   */
  public static function get_loteria_result($loteria, $concurso = 'latest')
  {
    $cache_key = "loteria_{$loteria}_{$concurso}";
    $result = wp_cache_get($cache_key);

    // Se não estiver no cache, fazer a chamada à API.
    if (false === $result) {
      $api_url = self::API_URL_BASE . "{$loteria}/{$concurso}";

      //$response = wp_remote_get($api_url);
      $response = vip_safe_wp_remote_get($api_url);

      if (is_wp_error($response)) {
        return false;
      }

      $body = wp_remote_retrieve_body($response);
      $result = json_decode($body, true);

      // Se houver erro na resposta da API, retornar false.
      if (! isset($result['concurso'])) {
        return false;
      }

      // Cachear o resultado por 1 dia.
      wp_cache_set($cache_key, $result, '', DAY_IN_SECONDS);
    }

    return $result;
  }
}
