<?php

/**
 * Plugin Name: Resultados da Loteria
 * Description: Exibe os resultados das Loterias Caixa usando um shortcode.
 * Version: 1.0.0
 * Author: Thalis Costa
 */

if (! defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use LoteriasPlugin\LoteriasPostType;
use LoteriasPlugin\LoteriasShortcode;

class LoteriasResultado
{

    /**
     * Constructor that initializes the plugin's main functionalities.
     * 
     * - Registers the custom post type for lottery results.
     * - Registers the shortcode to display lottery results.
     * - Enqueues styles for the frontend.
     */
    public function __construct()
    {
        new LoteriasPostType();
        new LoteriasShortcode();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    /**
     * Enqueue the styles for the plugin.
     * 
     * This method adds the plugin's CSS styles to the front-end of the site.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('lottery-plugin', plugin_dir_url(__FILE__) . '/assets/css/style.css', [], '1.0.0');
    }

    /**
     * Runs when the plugin is activated.
     * 
     * This function flushes the WordPress rewrite rules to ensure that the custom post types 
     * and routes are properly set up.
     */
    public static function activate()
    {
        flush_rewrite_rules();
    }

    /**
     * Runs when the plugin is deactivated.
     * 
     * This function also flushes the rewrite rules to clean up any custom routes or post types 
     * added by the plugin.
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}

if (class_exists('LoteriasResultado')) {
    new LoteriasResultado();
}

register_activation_hook(__FILE__, ['LoteriasResultado', 'activate']);
register_deactivation_hook(__FILE__, ['LoteriasResultado', 'deactivate']);
