<?php

namespace PSCNN\Modules;
use PSCNN\Modules\Thirdy\Renderer;

class Shortcodes {
    static public function pscnn_loterias($atts) {
        if (is_admin() || wp_is_json_request()) {
            return;
        }

        $loteria = $atts['loteria'] ?? 'megasena';
        $concurso = $atts['concurso'] ?? 'ultimo';

        return Renderer::renderFile(
            __DIR__ . '/../views/main.php.pug',
            compact('concurso', 'loteria'),
        );
    }

    static public function print_shortcode($shortcode) {
        echo do_shortcode($shortcode);
    }

    static public function init() {
        add_shortcode('pscnn-loterias', self::class . '::pscnn_loterias');
    }
}
