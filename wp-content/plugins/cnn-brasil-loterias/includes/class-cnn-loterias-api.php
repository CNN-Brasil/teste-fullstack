<?php
/**
 * CNN Loterias API Class
 *
 * This class handles API interactions for lottery results.
 *
 * @package CNN_Brasil_Loterias
 */

/**
 * Class CNN_Loterias_API
 */
class CNN_Loterias_API {

	/**
	 * Fetch lottery results from the API.
	 *
	 * @param string $loteria  The lottery type.
	 * @param string $concurso The lottery draw number.
	 * @return array|WP_Error The lottery results or an error object.
	 */
	public static function fetch_results( $loteria, $concurso ) {
		$transient_key = "cnn_loteria_{$loteria}_{$concurso}";
		$result        = false;

		if ( false === $result ) {
			$url = "https://loteriascaixa-api.herokuapp.com/api/{$loteria}/{$concurso}";
			if ( 'ultimo' === $concurso || 'latest' === $concurso ) {
				$url = "https://loteriascaixa-api.herokuapp.com/api/{$loteria}/latest";
			}

			$response = wp_remote_get( $url );
			if ( is_wp_error( $response ) ) {
				return new WP_Error( 'api_error', 'Failed to fetch lottery data' );
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );

			if ( empty( $data ) || ! is_array( $data ) ) {
				return new WP_Error( 'invalid_data', 'Invalid data received from API' );
			}

			$validation_result = self::validate_api_response( $data );
			if ( is_wp_error( $validation_result ) ) {
				return $validation_result;
			}

			$result = self::standardize_result( $data );
			set_transient( $transient_key, $result, HOUR_IN_SECONDS );
		}

		return $result;
	}

	/**
	 * Validate the API response.
	 *
	 * @param array $data The API response data.
	 * @return bool|WP_Error True if valid, WP_Error otherwise.
	 */
	private static function validate_api_response( $data ) {
		if ( isset( $data['name'] ) && isset( $data['concurso'] ) && isset( $data['data'] ) && isset( $data['numeros'] ) ) {
			return true;
		}

		if ( isset( $data['loteria'] ) && isset( $data['concurso'] ) && isset( $data['data'] ) && isset( $data['dezenas'] ) ) {
			return true;
		}

		return new WP_Error(
			'invalid_data_structure',
			'Invalid data structure received from API',
			array( 'data' => $data )
		);
	}

	/**
	 * Standardize the API result.
	 *
	 * @param array $data The API response data.
	 * @return array Standardized result.
	 */
	private static function standardize_result( $data ) {
		if ( isset( $data['name'] ) ) {
			return self::standardize_specific_concurso( $data );
		} else {
			return self::standardize_latest_concurso( $data );
		}
	}

	/**
	 * Standardize result for a specific concurso.
	 *
	 * @param array $data The API response data.
	 * @return array Standardized result.
	 */
	private static function standardize_specific_concurso( $data ) {
		return array(
			'loteria'    => $data['name'],
			'concurso'   => $data['concurso'],
			'data'       => $data['data'],
			'dezenas'    => $data['numeros'],
			'premiacoes' => array(),
		);
	}

	/**
	 * Standardize result for the latest concurso.
	 *
	 * @param array $data The API response data.
	 * @return array Standardized result.
	 */
	private static function standardize_latest_concurso( $data ) {
		return array(
			'loteria'                      => $data['loteria'],
			'concurso'                     => $data['concurso'],
			'data'                         => $data['data'],
			'dezenas'                      => $data['dezenas'],
			'premiacoes'                   => $data['premiacoes'] ?? array(),
			'acumulou'                     => $data['acumulou'] ?? false,
			'valorEstimadoProximoConcurso' => $data['valorEstimadoProximoConcurso'] ?? 0,
		);
	}

	/**
	 * Get existing post for a lottery draw.
	 *
	 * @param string $loteria  The lottery type.
	 * @param string $concurso The lottery draw number.
	 * @return WP_Post|false The existing post or false if not found.
	 */
	private static function get_existing_post( $loteria, $concurso ) {
		global $wpdb;

		$cache_key = "cnn_loteria_post_{$loteria}_{$concurso}";
		$post_id   = wp_cache_get( $cache_key );

		if ( false === $post_id ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Caching is implemented
			$post_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT p.ID
					FROM {$wpdb->posts} p
					JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_cnn_loteria_concurso'
					JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_cnn_loteria_name'
					WHERE p.post_type = 'loteria'
					AND pm1.meta_value = %s
					AND pm2.meta_value = %s
					LIMIT 1",
					$concurso,
					$loteria
				)
			);

			$post_id = $post_id ? (int) $post_id : 0;
			wp_cache_set( $cache_key, $post_id, '', HOUR_IN_SECONDS );
		}

		return $post_id ? get_post( $post_id ) : false;
	}

	/**
	 * Save lottery result to custom post type.
	 *
	 * @param array $data The lottery result data.
	 */
	private static function save_result_to_post_type( $data ) {
		$post_id = wp_insert_post(
			array(
				'post_title'  => sprintf( 'Loteria %s - Concurso %s', $data['loteria'], $data['concurso'] ),
				'post_type'   => 'loteria',
				'post_status' => 'publish',
			)
		);

		if ( ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_cnn_loteria_concurso', $data['concurso'] );
			update_post_meta( $post_id, '_cnn_loteria_name', $data['loteria'] );
			update_post_meta( $post_id, '_cnn_loteria_data', $data );
		}
	}
}
