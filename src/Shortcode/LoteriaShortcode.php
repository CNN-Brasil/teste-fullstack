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

namespace Cnnbr\TesteFullstack\Shortcode;

use Cnnbr\TesteFullstack\Api\LoteriaApi;

class LoteriaShortcode
{
    /**
     * Registers the shortcode.
     */
    public function register()
    {
        add_shortcode('loteria', [$this, 'renderShortcode']);
    }

    /**
     * Renders the lottery shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string|null Shortcode output or error message.
     */
    public function renderShortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'loteria' => 'megasena',
                'concurso' => 'ultimo',
            ),
            $atts,
            'loteria'
        );

        $concurso = sanitize_text_field($atts['concurso']);
        $loteria = sanitize_text_field($atts['loteria']);

        // Verificação dos valores válidos para loteria
        $valid_loterias = [
            'maismilionaria', 'megasena', 'lotofacil', 'quina', 'lotomania',
            'timemania', 'duplasena', 'federal', 'diadesorte', 'supersete'
        ];

        if (!in_array($loteria, $valid_loterias, true)) {
            return 'Loteria inválida.';
        }

        if ($concurso !== 'ultimo' && !is_numeric($concurso)) {
            return 'Concurso inválido.';
        }

        $api = new LoteriaApi();
        $data = $api->getData($loteria, $concurso);

        if ($data === 'Loteria inválida.' || $data === 'Concurso inválido.' || strpos($data, 'Erro') !== false) {
            return $data;
        }

        ob_start();
        include plugin_dir_path(__FILE__) . '../../templates/shortcode-output.php';
        return ob_get_clean();
    }
}
