<?php
/*
Plugin Name: Loterias CAIXA
Description: Plugin para exibir resultados das loterias da CAIXA.
Plugin URI: https://github.com/adomoraes/teste-fullstack
Version: 1.0
Author: Eduardo Moraes
Author URI: https://github.com/adomoraes/
*/

function loterias_caixa_shortcode($atts) {

    $atts = shortcode_atts(array(
        'loteria' => '0',
        'concurso' => '0',
    ), $atts);

    $loteria = $atts['loteria'];
    $concurso = $atts['concurso'];

    $html = '<div class="loterias-caixa">';
    $html .= '<h2>Resultados das Loterias CAIXA</h2>';
    $html .= '<p>Loteria:</p>' . $loteria;
    $html .= '<p>Concurso:</p>' . $concurso;
    $html .= '</div>';

    return $html;
}
add_shortcode('loterias_caixa', 'loterias_caixa_shortcode');
