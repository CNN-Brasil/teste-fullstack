<?php

/**
 * Internal Loterias
 *
 * @package Loterias
 */

/*
Plugin Name: Loterias
Plugin URI: https://github.com/ctoveloz/teste-fullstack
Description: Loterias Tigrin
Version: 1.0.0
Author: Cristiano Matos
License: MIT
Copyright: Copyright (c) 2024, Cristiano Matos
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Currently plugin version.
 */
define('LOTERIAS_VERSION', '1.0.0');

/**
 * Current plugin path.
 */
define('LOTERIAS_PATH', plugin_dir_path(__FILE__));

require_once LOTERIAS_PATH . 'vendor/autoload.php';

/**
 * Define URL API
 */
if (!defined('LOTERIAS_API_URL')) {
    define('LOTERIAS_API_URL', 'https://loteriascaixa-api.herokuapp.com/api/');
}

/**
 * The code that runs during plugin activation.
 */
function activate_loterias()
{
    Cnnbr\TesteFullstack\Activator::activate();
}
register_activation_hook(__FILE__, 'activate_loterias');

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_loterias()
{
    Cnnbr\TesteFullstack\Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_loterias');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_loterias()
{
    $plugin = new Cnnbr\TesteFullstack\Plugin();
    $plugin->run();
}
add_action('plugins_loaded', 'run_loterias');

/**
 * Enqueue custom plugin styles
 */
function enqueue_custom_plugin_styles()
{
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('custom-plugin-styles', $plugin_url . 'assets/css/style.css');

    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap', [], null);

    add_action('wp_head', function () {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    });
}

add_action('wp_enqueue_scripts', 'enqueue_custom_plugin_styles');
