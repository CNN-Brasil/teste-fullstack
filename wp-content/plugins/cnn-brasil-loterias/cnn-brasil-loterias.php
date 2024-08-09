<?php
/**
 * Plugin Name: CNN Brasil Loterias
 * Description: A plugin to display Caixa Lottery results using a shortcode.
 * Version: 1.0
 * Author: Ramon Mendes
 *
 * @package CNN_Brasil_Loterias
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'CNN_LOTERIAS_VERSION', '1.0.0' );
define( 'CNN_LOTERIAS_PLUGIN_FILE', __FILE__ );
define( 'CNN_LOTERIAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CNN_LOTERIAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the main CNN_Brasil_Loterias class.
require_once CNN_LOTERIAS_PLUGIN_DIR . 'includes/class-cnn-brasil-loterias.php';

/**
 * Main instance of CNN_Brasil_Loterias.
 *
 * Returns the main instance of CNN_Brasil_Loterias to prevent the need to use globals.
 *
 * @since 1.0.0
 * @return CNN_Brasil_Loterias
 */
function cnn_brasil_loterias() {
	return CNN_Brasil_Loterias::instance();
}


// Initialize the plugin.
cnn_brasil_loterias();
