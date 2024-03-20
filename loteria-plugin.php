<?php
/**
 * @package LCNN
 * @author  Adriano Franco

	Plugin Name:       Loterias CNN
	Description:       Plugin criado para avaliação de conhecimento
	Version:           Alpha 1.0.0
	Author:            Adriano Franco
	Author URI:        https://linkedin.com/in/adrianofranco
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* CHAMANDO OS REQUIRE DAS CLASSES A SEREM INTANCIADAS.
* -- Resolvi não utilizar autoloader composer pois o teste pedia que não deveria ter nenhum ajuste previo
*/
require_once plugin_dir_path( __FILE__ ) . 'src/includes/base.php';
require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-lcnn-api.php';
require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-lcnn-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'src/includes/class-lcnn-type-post.php';
require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-lcnn-utils.php';

/**
 * ATIAVNDO O PLUGIN.
 */
function active_plugin(): void {
	$post_type = new LCNN_Post_Type();
	$post_type->register_post_type();
}//end active_plugin()
register_activation_hook( __FILE__, 'active_plugin' );

new LCNN_Post_Type();
new LCNN_Shortcode();
