<?php

class Loterias_Shortcode {
    public static function register() {
        add_shortcode('loterias_result', array(__CLASS__, 'display_results'));
    }

    public static function display_results($atts) {
        $atts = shortcode_atts(array(
            'loteria' => '',
            'concurso' => '',
        ), $atts);

        $concurso = sanitize_text_field($atts['concurso']);
        $loteria = sanitize_text_field($atts['loteria']);

        // Verifica se o resultado já está no cache ou no post-type
        $results = Loterias_Cache::get_results($loteria, $concurso);

        if (!$results) {
            $results = Loterias_API::get_results($loteria, $concurso);
            Loterias_Cache::set_results($loteria, $concurso, $results);
            Loterias_CPT::save_results($loteria, $concurso, $results);
        }

        ob_start();
        echo Loterias_CPT::format_results_table($results);
        return ob_get_clean();
    }

        

    
}
