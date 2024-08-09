<?php
/**
 * CNN Loterias API Class
 *
 * This class handles API interactions for lottery results.
 *
 * @package CNN_Brasil_Loterias
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path(__FILE__) . 'class-redis-client.php';

/**
 * CNN_Loterias_API Class.
 */
class CNN_Loterias_API {
    /**
     * Initialize the API class.
     *
     * @since 1.0.0
     */
    public static function init() {
        // Initialization code here if needed.
    }

    /**
     * Fetch lottery results from the API.
     *
     * @since 1.0.0
     * @param string $loteria The lottery type.
     * @param string $concurso The lottery draw number.
     * @return array|WP_Error The lottery results or WP_Error on failure.
     */
    public static function fetch_results( $loteria, $concurso ) {
        $cache_key = "cnn_loteria_{$loteria}_{$concurso}";
        
        $redis_client = new Redis_Client();
        $result = $redis_client->get($cache_key);

        if ($result === false) {
            $url = self::get_api_url( $loteria, $concurso );
            $response = wp_remote_get( $url );

            if ( is_wp_error( $response ) ) {
                return new WP_Error( 'api_error', __( 'Failed to fetch lottery data', 'cnn-brasil-loterias' ) );
            }

            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );

            if ( empty( $data ) || ! is_array( $data ) ) {
                return new WP_Error( 'api_error', __( 'Invalid data received from API', 'cnn-brasil-loterias' ) );
            }

            $result = self::standardize_result( $data );

            // Store the result in Redis for 1 hour
            $redis_client->set($cache_key, serialize($result), 3600);
        } else {
            $result = unserialize($result);
        }

        return $result;
    }

    /**
     * Get the API URL based on lottery type and draw number.
     *
     * @since 1.0.0
     * @param string $loteria The lottery type.
     * @param string $concurso The lottery draw number.
     * @return string The API URL.
     */
    private static function get_api_url( $loteria, $concurso ) {
        $base_url = 'https://loteriascaixa-api.herokuapp.com/api';
        return ( 'ultimo' === $concurso || 'latest' === $concurso )
            ? "{$base_url}/{$loteria}/latest"
            : "{$base_url}/{$loteria}/{$concurso}";
    }

    /**
     * Standardize the API result.
     *
     * @since 1.0.0
     * @param array $data The API response data.
     * @return array Standardized result.
     */
    private static function standardize_result( $data ) {
        return array(
            'loteria' => $data['loteria'] ?? '',
            'concurso' => $data['concurso'] ?? '',
            'data' => $data['data'] ?? '',
            'dezenas' => $data['dezenas'] ?? array(),
            'premiacoes' => $data['premiacoes'] ?? array(),
            'acumulou' => $data['acumulou'] ?? false,
            'valorEstimadoProximoConcurso' => $data['valorEstimadoProximoConcurso'] ?? 0,
        );
    }
}