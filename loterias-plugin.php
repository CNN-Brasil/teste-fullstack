<?php
/**
 * Plugin Name: Loterias Caixa Results
 * Description: Exibe os resultados dos jogos das Loterias Caixa usando um shortcode.
 * Version: 1.0.0
 * Author: Leonardo Lima
 * Text Domain: loterias-plugin
 */

// Definindo constantes
define( 'LOTERIAS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LOTERIAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Carregar os arquivos necessários
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-cpt.php';
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-shortcode.php';
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-api.php';
require_once LOTERIAS_PLUGIN_PATH . 'includes/class-loterias-cache.php';

// Inicializa as classes
function loterias_plugin_init() {
    Loterias_CPT::register();
    Loterias_Shortcode::register();
}
add_action( 'init', 'loterias_plugin_init' );
