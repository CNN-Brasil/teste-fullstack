<?php

/**
 * Internal Loterias
 *
 * @package Loterias
 */

/*
Plugin Name: Loterias
Plugin URI: https://github.com/ctoveloz/teste-fullstack
Description: Loterias Tigrin
Version: 1.0.0
Author: Cristiano Matos
License: MIT
Copyright: Copyright (c) 2024, Cristiano Matos
*/

namespace Cnnbr\TesteFullstack\Api;

use WP_Query;

class LoteriaApi
{
    const BASE_URL = LOTERIAS_API_URL;
    const CACHE_EXPIRATION = 12 * HOUR_IN_SECONDS;

    /**
     * Fetch lottery data from API or cache.
     *
     * @param string $loteria
     * @param string $concurso
     * @return string
     */
    public function getData($loteria, $concurso)
    {
        $loteria = sanitize_text_field($loteria);
        $concurso = sanitize_text_field($concurso);

        if (!$this->isValidLoteria($loteria)) {
            return 'Loteria inválida.';
        }

        if (!$this->isValidConcurso($concurso)) {
            return 'Concurso inválido.';
        }

        $cache_key = "loteria_{$loteria}_concurso_{$concurso}";
        $cached_data = get_transient($cache_key);
        if ($cached_data !== false) {
            return $cached_data;
        }

        $data = $concurso === 'ultimo' ? $this->getLatestData($loteria) : $this->getSpecificData($loteria, $concurso);
        if (!$data || !$this->isValidResponse($data)) {
            return 'Dados inválidos recebidos da API.';
        }

        set_transient($cache_key, $data, self::CACHE_EXPIRATION);
        return $data;
    }

    /**
     * Validate the lottery type.
     *
     * @param string $loteria
     * @return bool
     */
    private function isValidLoteria($loteria)
    {
        $valid_loterias = ['megasena', 'quina', 'lotofacil', 'lotomania', 'duplasena', 'federal', 'diadesorte', 'supersete'];
        return in_array($loteria, $valid_loterias, true);
    }

    /**
     * Validate the contest type.
     *
     * @param string $concurso
     * @return bool
     */
    private function isValidConcurso($concurso)
    {
        return is_numeric($concurso) || $concurso === 'ultimo';
    }

    /**
     * Fetch latest lottery data.
     *
     * @param string $loteria
     * @return string|null
     */
    private function getLatestData($loteria)
    {
        $response = wp_remote_get(self::BASE_URL . $loteria . '/latest');
        return $this->handleApiResponse($response);
    }

    /**
     * Fetch specific contest data.
     *
     * @param string $loteria
     * @param string $concurso
     * @return string|null
     */
    private function getSpecificData($loteria, $concurso)
    {
        $query = new WP_Query([
            'post_type' => 'loterias',
            'meta_query' => [
                [
                    'key' => 'concurso',
                    'value' => $concurso,
                ],
            ],
        ]);

        if ($query->have_posts()) {
            return $query->posts[0]->post_content;
        }

        $response = wp_remote_get(self::BASE_URL . $loteria . '/' . $concurso);
        return $this->handleApiResponse($response);
    }

    /**
     * Handle API response.
     *
     * @param WP_Error|array $response
     * @return string|null
     */
    private function handleApiResponse($response)
    {
        if (is_wp_error($response)) {
            return 'Erro ao buscar dados da API.';
        }

        $data = wp_remote_retrieve_body($response);
        if ($this->isValidResponse($data)) {
            $this->saveData($data);
            return $data;
        }

        return null;
    }

    /**
     * Save lottery data to the database.
     *
     * @param string $data
     * @return void
     */
    private function saveData($data)
    {
        $decoded_data = json_decode($data, true);
        $concurso = $decoded_data['concurso'];

        $query = new WP_Query([
            'post_type' => 'loterias',
            'meta_query' => [
                [
                    'key' => 'concurso',
                    'value' => $concurso,
                ],
            ],
        ]);

        if ($query->have_posts()) {
            return;
        }

        $post_data = [
            'post_title' => "{$decoded_data['loteria']} - Concurso #{$concurso}",
            'post_content' => $data,
            'post_status' => 'publish',
            'post_type' => 'loterias',
            'meta_input' => [
                'concurso' => $concurso,
            ],
        ];

        wp_insert_post($post_data);
    }

    /**
     * Validate API response.
     *
     * @param string $data
     * @return bool
     */
    private function isValidResponse($data)
    {
        $decoded_data = json_decode($data, true);
        return isset($decoded_data['concurso'], $decoded_data['loteria'], $decoded_data['dezenas']);
    }
}
