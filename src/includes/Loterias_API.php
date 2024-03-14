<?php
namespace Cnnbr\TesteFullstack\includes;

/**
 * Gerencia a comunicação com a API das Loterias da Caixa.
 *
 * @package LoteriasCaixa
 */

/**
 * Classe para interagir com a API de loterias.
 */
class Loterias_API {

	/**
	 * URL base da API de loterias.
	 *
	 * @var string
	 */
	private $base_api_url = 'https://loteriascaixa-api.herokuapp.com/api';

	/**
	 * Obtém os dados de um concurso específico ou o último concurso.
	 *
	 * @param string $loteria Nome da loteria.
	 * @param string $concurso Número do concurso ou 'ultimo'.
	 * @return string Dados do concurso em formato JSON.
	 */
	public function obter_dados_concurso( $loteria, $concurso ) {
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

				// Verifica se já existe um post com o mesmo título.
				$query = new \WP_Query(
					array(
						'post_type'      => 'loterias',
						'post_title'     => "Loteria {$loteria} - Concurso {$concurso_numero}",
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
								'loteria'  => $loteria,
								'concurso' => $concurso_numero,
							),
						)
					);
				}

				$resultado = $body;
				wp_cache_set( $cache_key, $resultado, '', 3600 );
			} else {
				return "Não foi possível obter os dados para a loteria {$loteria} e concurso {$concurso}.";
			}
		}

		return $resultado;
	}
}