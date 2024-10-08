<?php
/**
 * Plugin Name: Loterias Caixa Plugin
 * Description: Exibe resultados das Loterias Caixa com base em dados da API externa.
 * Version: 1.0
 * Author: Eduardo Moraes
 * Author URI: https://github.com/adomoraes/loterias-plugin
 * Text Domain: loterias-plugin
 *
 * @package loterias-plugin
 */

if (!defined('ABSPATH')) {
    exit; // Impede acesso direto
}

// Inclui as classes necessárias
require_once plugin_dir_path(__FILE__) . 'includes/class-loterias-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-loterias-cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-loterias-shortcode.php';

// Registra o post type "Loterias" e o shortcode no momento de ativação
function loterias_init_plugin() {
    $loterias_cpt = new Loterias_CPT();
    $loterias_shortcode = new Loterias_Shortcode();
}
add_action('init', 'loterias_init_plugin');
function loterias_enqueue_styles() {
    wp_enqueue_style('loterias-styles', plugin_dir_url(__FILE__) . 'assets/styles.css');
}
add_action('wp_enqueue_scripts', 'loterias_enqueue_styles');