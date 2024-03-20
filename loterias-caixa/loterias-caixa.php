<?php
/*
Plugin Name: Loterias CAIXA
Description: Plugin para exibir resultados das loterias da CAIXA.
Plugin URI: https://github.com/adomoraes/teste-fullstack
Version: 1.0
Author: Eduardo Moraes
Author URI: https://github.com/adomoraes/
*/

function loterias_caixa_shortcode() {

    $html = '<div class="loterias-caixa">';
    $html .= '<h2>Resultados das Loterias CAIXA</h2>';
    $html .= '</div>';

    return $html;
}
add_shortcode('loterias_caixa', 'loterias_caixa_shortcode');
