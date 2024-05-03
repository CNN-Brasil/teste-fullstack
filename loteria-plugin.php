<?php
/**
 * @package LoteriasCaixaCNN
 * @author  João Vagner

	Plugin Name:       Loterias Caixa CNN
	Description:       Plugin desenvolvido para teste de conhecimento técnico
	Version:           1.0.0
	Author:            João Vagner
	Author URI:        https://www.linkedin.com/in/wagnerlemos
	Plugin URI:        https://www.linkedin.com/in/wagnerlemos
	Text Domain: loterias-caixa-CNN
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* CARREFA AS CLASSES A SEREM INTANCIADAS.
*/
require_once plugin_dir_path( __FILE__ ) . 'src/includes/lc_cnn.php';
require_once plugin_dir_path( __FILE__ ) . 'src/includes/loteriasCaixaCNN-API.php';
require_once plugin_dir_path( __FILE__ ) . 'src/includes/loteriasCaixaCNN-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'src/includes/loteriasCaixaCNN-type-post.php';
require_once plugin_dir_path( __FILE__ ) . 'src/includes/loteriasCaixaCNN-utils.php';

/**
 * ATIVA O PLUGIN.
 */
function active_plugin(): void {
	$post_type = new loteriasCaixaCNN_Post_Type();
	$post_type->register_post_type();
}
register_activation_hook( __FILE__, 'active_plugin' );

new loteriasCaixaCNN_Post_Type();
new loteriasCaixaCNN_Shortcode();