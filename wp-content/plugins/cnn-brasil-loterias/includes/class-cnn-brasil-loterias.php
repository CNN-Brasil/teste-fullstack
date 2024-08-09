<?php
/**
 * The main plugin class.
 *
 * @package CNN_Brasil_Loterias
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main CNN_Brasil_Loterias Class.
 *
 * @class CNN_Brasil_Loterias
 */
final class CNN_Brasil_Loterias {

	/**
	 * The single instance of the class.
	 *
	 * @var CNN_Brasil_Loterias
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main CNN_Brasil_Loterias Instance.
	 *
	 * Ensures only one instance of CNN_Brasil_Loterias is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return CNN_Brasil_Loterias - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * CNN_Brasil_Loterias Constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Enqueue styles for the plugin.
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'cnn-brasil-loterias-style',
			CNN_LOTERIAS_PLUGIN_URL . 'assets/css/styles.css',
			array(),
			CNN_LOTERIAS_VERSION
		);
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		include_once CNN_LOTERIAS_PLUGIN_DIR . 'includes/class-cnn-loterias-api.php';
		include_once CNN_LOTERIAS_PLUGIN_DIR . 'includes/class-cnn-loterias-shortcode.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'init', array( 'CNN_Loterias_API', 'init' ) );
		add_action( 'init', array( 'CNN_Loterias_Shortcode', 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}
}
