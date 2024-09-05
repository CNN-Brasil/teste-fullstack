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

    public function __construct()
    {
        new LoteriasPostType();
        new LoteriasShortcode();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('lottery-plugin', plugin_dir_url(__FILE__) . '/assets/css/style.css', [], '1.0.0');
    }

    public static function activate()
    {
        flush_rewrite_rules();
    }

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
