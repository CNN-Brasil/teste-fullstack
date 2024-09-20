<?php
/*
Plugin Name: Loterias Caixa Resultados
Description: Plugin para exibir os resultados das Loterias Caixa via shortcode.
Version: 1.0
Author: Thiago Cintas
*/

// Carregar arquivos necessários
require_once plugin_dir_path(__FILE__) . 'includes/logic.php';
require_once plugin_dir_path(__FILE__) . 'includes/front.php';


register_activation_hook(__FILE__, 'loterias_plugin_activate');

register_deactivation_hook(__FILE__, 'loterias_plugin_deactivate');

function loterias_caixa_enqueue_styles() {
    wp_enqueue_style('loterias-caixa-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'loterias_caixa_enqueue_styles');

add_shortcode('loterias', array('LoteriasCaixa\includes\Loteria_Front', 'exibe_loteria'));
