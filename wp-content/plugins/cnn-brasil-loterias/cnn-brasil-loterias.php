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

// Include necessary files.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cnn-loterias-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cnn-loterias-shortcode.php';

/**
 * Register post type on plugin activation.
 */
function cnn_register_loterias_post_type() {
	$labels = array(
		'name'               => _x( 'Loterias', 'post type general name', 'cnn-brasil-loterias' ),
		'singular_name'      => _x( 'Loteria', 'post type singular name', 'cnn-brasil-loterias' ),
		'menu_name'          => _x( 'Loterias', 'admin menu', 'cnn-brasil-loterias' ),
		'name_admin_bar'     => _x( 'Loteria', 'add new on admin bar', 'cnn-brasil-loterias' ),
		'add_new'            => _x( 'Add New', 'loteria', 'cnn-brasil-loterias' ),
		'add_new_item'       => __( 'Add New Loteria', 'cnn-brasil-loterias' ),
		'new_item'           => __( 'New Loteria', 'cnn-brasil-loterias' ),
		'edit_item'          => __( 'Edit Loteria', 'cnn-brasil-loterias' ),
		'view_item'          => __( 'View Loteria', 'cnn-brasil-loterias' ),
		'all_items'          => __( 'All Loterias', 'cnn-brasil-loterias' ),
		'search_items'       => __( 'Search Loterias', 'cnn-brasil-loterias' ),
		'parent_item_colon'  => __( 'Parent Loterias:', 'cnn-brasil-loterias' ),
		'not_found'          => __( 'No loterias found.', 'cnn-brasil-loterias' ),
		'not_found_in_trash' => __( 'No loterias found in Trash.', 'cnn-brasil-loterias' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'loteria' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'loteria', $args );
}
add_action( 'init', 'cnn_register_loterias_post_type' );

/**
 * Enqueue scripts and styles.
 */
function cnn_enqueue_assets() {
	wp_enqueue_style( 'cnn-loterias-styles', plugins_url( 'assets/css/styles.css', __FILE__ ), array(), '1.0.0' );
	wp_enqueue_script( 'cnn-loterias-scripts', plugins_url( 'assets/js/scripts.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'cnn_enqueue_assets' );

/**
 * Register shortcode.
 */
function cnn_register_shortcode() {
	CNN_Loterias_Shortcode::register();
}
add_action( 'init', 'cnn_register_shortcode' );

/**
 * Activation hook.
 */
function cnn_loterias_activate() {
	cnn_register_loterias_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cnn_loterias_activate' );

/**
 * Deactivation hook.
 */
function cnn_loterias_deactivate() {
	unregister_post_type( 'loteria' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cnn_loterias_deactivate' );
