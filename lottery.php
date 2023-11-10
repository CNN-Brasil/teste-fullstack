<?php
/**
 * @package   teste-fullstack
 * @copyright 2023 CNN
 * @author    Angelo Rocha
 * @wordpress-plugin
 * Plugin Name:       Lottery
 * Plugin URI:        https://github.com/angelorocha/teste-fullstack/tree/master
 * Description:       Show lottery results
 * Version:           1.0.0
 * Author:            Angelo Rocha
 * Author URI:        https://www.angelorocha.com.br/
 * Text Domain:       cnn-lottery
 * Domain Path:       /lang
 * GitHub Plugin URI: https://github.com/angelorocha/teste-fullstack/tree/master
 */

namespace CnnPluginBr;

use CnnPluginBr\Admin\LotteryInit;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

/** Execute autoload */
$plugin_path = plugin_dir_path( __FILE__ );
require_once $plugin_path . 'autoload.php';
cnn_autoload( $plugin_path );

if ( ! defined( 'CNN_PLUGIN_PATH' ) ) {
    define( 'CNN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CNN_PLUGIN_URL' ) ) {
    define( 'CNN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/** Init plugin */
add_action( 'plugins_loaded', [ LotteryInit::class, 'getInstance' ], 0 );

/** Plugin activate action */
register_activation_hook( __FILE__, [ LotteryInit::class, 'pluginActivateAction' ] );

/** Plugin uninstall action */
register_deactivation_hook( __FILE__, [ LotteryInit::class, 'pluginUninstallAction' ] );
