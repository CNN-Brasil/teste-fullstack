<?php

namespace PSCNN\Modules;
use PSCNN\Modules\Thirdy\Renderer;

class Shortcodes {
    /**
     * Method Shortcodes::pscnn_loterias prints the content of the ['pscnn-loterias'] shortcode.
     *
     * @since 0.0.1
     *
     * @param array $atts - is provided automatically.
     *
     * @return void
     */

    static public function pscnn_loterias($atts): string {
        if (is_admin() || wp_is_json_request()) {
            return '';
        }

        $loteria = $atts['loteria'] ?? 'megasena';
        $concurso = $atts['concurso'] ?? 'ultimo';

        return Renderer::renderFile(
            __DIR__ . '/../views/main.php.pug',
            compact('concurso', 'loteria'),
        );
    }

    /**
     * Method Shortcodes::print_shortcode executes a shortcode on the back end.
     *
     * @since 0.0.1
     *
     * @param string $shortcode - must be the name of the shortcode with square brackets -> '[shortcode]'.
     *
     * @return void
     */

    static public function print_shortcode(string $shortcode): void {
        echo do_shortcode($shortcode);
    }

    /**
     * Method Shortcodes::init performs the initial actions of the class.
     * and is called by global initialization
     *
     * @since 0.0.1
     *
     * @return void
     */

    static public function init(): void {
        add_shortcode('pscnn-loterias', self::class . '::pscnn_loterias');
    }
}
