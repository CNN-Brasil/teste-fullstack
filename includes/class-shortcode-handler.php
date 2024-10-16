<?php

class Shortcode_Handler {
    public function render_shortcode($atts) {
        $atts = shortcode_atts([
            'loteria' => 'megasena',
            'concurso' => 'latest',
        ], $atts, 'loterias');

        $api = new API_Connector();
        $results = $api->get_results($atts['loteria'], $atts['concurso']);

        if (!$results) {
            return "Erro ao obter os resultados ou nenhum resultado encontrado.";
        }

        ob_start();
        include LOT_CX_DIR . 'views/result-template.php';
        return ob_get_clean();
    }
}
add_shortcode('loterias', [new Shortcode_Handler(), 'render_shortcode']);
