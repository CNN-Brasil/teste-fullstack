<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class LoteriasCaixa_API {

    private $base_api_url = 'https://loteriascaixa-api.herokuapp.com/api';
    
    public function obter_dados_concurso( $loteria, $concurso ) {
        // Validar entrada
        if ( empty( $loteria ) || empty( $concurso ) ) {
            return 'Loteria ou concurso inválido.';
        }

        $cache_key = "loterias_{$loteria}_{$concurso}";
        $resultado = wp_cache_get( $cache_key );

        if ( false === $resultado ) {
            $url = $this->base_api_url . "/{$loteria}/" . ( 'ultimo' === $concurso ? 'latest' : $concurso );

            $response = wp_remote_get( $url );

            if ( is_wp_error( $response ) ) {
                return 'Erro ao acessar a API.';
            }

            $body  = wp_remote_retrieve_body( $response );
            $dados = json_decode( $body, true );

            if ( ! is_null( $dados ) && isset( $dados['concurso'] ) ) {
                $concurso_numero = $dados['concurso'];

                // Verifica se já existe um post com o mesmo número do concurso.
                $query = new \WP_Query(
                    array(
                        'post_type'      => 'loterias',
                        'meta_query'     => array(
                            array(
                                'key'     => 'concurso',
                                'value'   => $concurso_numero,
                                'compare' => '=',
                            ),
                        ),
                        'posts_per_page' => 1,
                    )
                );

                if ( ! $query->have_posts() ) {
                    $post_id = wp_insert_post(
                        array(
                            'post_title'   => "Loteria {$loteria} - Concurso {$concurso_numero}",
                            'post_content' => $body,
                            'post_status'  => 'publish',
                            'post_type'    => 'loterias',
                            'meta_input'   => array(
                                'loteria'       => $loteria,
                                'concurso'      => $concurso_numero,
                                'data_concurso' => $dados['data'],
                            ),
                        )
                    );
                }

                $resultado = $body;
                wp_cache_set( $cache_key, $resultado, '', 3600 ); // Cache de uma hora
            } else {
                return "<p class='error'>Não foi possível obter os dados para a loteria {$loteria} e concurso {$concurso}.</p>";
            }
        }

        return $resultado;
    }
}
