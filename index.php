<?php
/*
Plugin Name: Resultados Loterias Caixa
Description: Exibe os resultados das Loterias Caixa usando um shortcode.
Version: 1.0
Author: Eric Moraes
*/

if (!defined('ABSPATH')) {
    exit; // Evita acesso direto
}

// Inclui as funções principais
require_once plugin_dir_path(__FILE__) . 'includes/LoteriasPostType.php';
require_once plugin_dir_path(__FILE__) . 'includes/LoteriasShortCode.php';
require_once plugin_dir_path(__FILE__) . 'includes/LoteriasAPI.php';
require_once plugin_dir_path(__FILE__) . 'includes/LoteriasRender.php';

// Registra os scripts e estilos
function lc_enqueue_styles() {
    wp_enqueue_style('', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'lc_enqueue_styles');
?>
