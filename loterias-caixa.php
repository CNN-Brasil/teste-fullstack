<?php
/**
 * Plugin Name: Loterias Caixa
 * Plugin URI: https://github.com/Armandomateus41
 * Description: Exibe os resultados dos jogos das Loterias Caixa.
 * Version: 1.0.0
 * Author: Armando Mateus Capita
 * Author URI: https://github.com/Armandomateus41
 * License: GPL2
 */

 if (!defined('ABSPATH')) {
     exit; 
 }
 
 
 define('LOT_CX_DIR', plugin_dir_path(__FILE__));
 define('LOT_CX_URL', plugin_dir_url(__FILE__));
 
 
 require_once LOT_CX_DIR . 'includes/class-api-connector.php';
 require_once LOT_CX_DIR . 'includes/class-shortcode-handler.php';
 
 
 function lot_cx_enqueue_assets() {
     wp_enqueue_style('loterias-caixa-style', LOT_CX_URL . 'public/css/style.css');
     wp_enqueue_script('loterias-caixa-script', LOT_CX_URL . 'public/js/script.js', array('jquery'), null, true);
 }
 add_action('wp_enqueue_scripts', 'lot_cx_enqueue_assets');