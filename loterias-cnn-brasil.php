<?php

/**
 * Plugin Name: Loterias CNN Brasil
 * Description: Exibe os resultados das Loterias Caixa via shortcode.
 * Version: 1.0
 * Author: Leonardo Pang
 * License: GPLv2 or later
 */

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Autoload classes
require_once plugin_dir_path(__FILE__) . 'src/classes/class-loteria-api.php';
require_once plugin_dir_path(__FILE__) . 'src/classes/class-loteria-cpt.php';
require_once plugin_dir_path(__FILE__) . 'src/classes/class-loteria-shortcode.php';

// Importar funções
require_once plugin_dir_path(__FILE__) . 'src/includes/functions.php';

// Função para registrar o CSS
function cnnbr_loterias_enqueue_styles()
{
  wp_enqueue_style(
    'loteria-style',
    plugin_dir_url(__FILE__) . 'assets/css/loteria-style.css',
    array(),
    '0.5',
    'all'
  );
}
add_action('wp_enqueue_scripts', 'cnnbr_loterias_enqueue_styles');

// Inicializar CPT e Shortcode
function loterias_cnn_init()
{
  Loteria_CPT::register_post_type();
  Loteria_Shortcode::register_shortcode();
}
add_action('init', 'loterias_cnn_init');
