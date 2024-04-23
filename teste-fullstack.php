<?php
/**
 * Plugin Name: Teste Fullstack - CNN Brasil
 * Version: 1.0
 * Author: Lucas Trindade
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'classes/TesteFullstack.php';
$teste_fullstack = new TesteFullstack();