<?php

namespace LotteryChallenge;

/**
 * Class LotteryAPI
 * @package LotteryChallenge
 * 
 * API para consulta de resultados de loterias
 */
class LotteryAPI
{
    /**
     * @var string $api_url URL base da API
     */
    private $api_url = 'https://loteriascaixa-api.herokuapp.com/api';

    /**
     * Obtém os resultados de um concurso de loteria.
     * 
     * @param string $lottery Nome da loteria
     * @param string $contest Número do concurso
     * @return array Dados do concurso ou falso se houver um erro
     */
    public function get_lottery_results($lottery, $contest)
    {
        $endpoint = sprintf('%s/%s/%s', $this->api_url, $lottery, $contest);

        $response = wp_remote_get($endpoint);

        if($response['response']['code'] == 404) {
            return [
                'error' => true,
                'message' => 'Concurso não encontrado'
            ];
        }

        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }
}