<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class LoteriasCaixa_API {

    private $base_api_url = 'https://loteriascaixa-api.herokuapp.com/api';
    
    public function get_concurso( $loteria, $concurso ) {
        // Validar entrada
        if ( empty( $loteria ) || empty( $concurso ) ) {
            return false;
        }

        //$cache_key = "loterias_{$loteria}_{$concurso}";
        //$resultado = wp_cache_get( $cache_key );
        
            $url = $this->base_api_url . "/{$loteria}/" . ( $concurso === 'ultimo' ? 'latest' : $concurso );

            $response = wp_remote_get( $url );

            if ( is_wp_error( $response ) ) {
                $error_message = 'Erro ao acessar a API.';
                $error_json = wp_json_encode( array( 'error' => $error_message ) );
                return $error_json;
            }

            $body  = wp_remote_retrieve_body( $response );
            $dados = json_decode( $body, true );

            if ( ! is_null( $dados ) && isset( $dados['concurso'] ) ) {
                $concurso_numero = $dados['concurso'];

                $resultado = $body;
                //wp_cache_set( $cache_key, $resultado, '', 3600 ); // Cache de uma hora
            } else {
                return "<p class='error'>Não foi possível obter os dados para a loteria {$loteria} e concurso {$concurso}.</p>";
            }
        

        return $resultado;
    }
}
