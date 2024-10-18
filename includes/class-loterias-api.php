<?php

class Loterias_API {

    private $api_base = 'https://loteriascaixa-api.herokuapp.com/api/';

    // Função para buscar o último concurso de um jogo
    public function get_ultimo_concurso( $loteria ) {
        $url = $this->api_base . $loteria . '/latest';

        // Faz a requisição para a API
        $response = wp_remote_get( $url );
        if ( is_wp_error( $response ) ) {
            error_log( 'Erro ao conectar com a API: ' . $response->get_error_message() );
            return false;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! empty( $data ) && isset( $data['concurso'] ) ) {
            return $data;
        } else {
            error_log( 'Erro: Dados inválidos retornados da API.' );
            return false; 
        }
    }
}