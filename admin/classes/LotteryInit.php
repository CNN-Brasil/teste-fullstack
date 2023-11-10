<?php

namespace CnnPluginBr\Admin;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

/**
 * Class PluginInit
 * @since 1.0.0
 */
class LotteryInit {
    /**
     * Instance of this class.
     *
     * @var object|null
     * @since 1.0.0
     */
    protected static ?object $instance = null;
    
    /**
     * Prefix plugin
     *
     * @var string
     * @since 1.0.0
     */
    private static string $prefix = 'cnn-lottery';
    
    /**
     * Define the plugin script object name
     *
     * @var string
     * @since 1.0.0
     */
    private static string $global_params_object_name = 'global_params_lottery';
    
    /**
     * Define assets version
     *
     * @var int
     * @since 1.0.0
     */
    private static int $file_version = 20231109;
    
    /**
     * Define admin css handle
     *
     * @var string
     * @since 1.0.0
     */
    private static string $admin_css_file = '-admin-main-css';
    
    /**
     * Define public css handle
     *
     * @var string
     * @since 1.0.0
     */
    private static string $public_css_file = '-main-css';
    
    /**
     * Define admin js handle
     *
     * @var string
     * @since 1.0.0
     */
    private static string $admin_js_file = '-admin-main-js';
    
    /**
     * Define public js handle
     *
     * @var string
     * @since 1.0.0
     */
    private static string $public_js_file = '-main-js';
    
    /**
     * Define plugin absolute path
     *
     * @var string|null
     * @since 1.0.0
     */
    public static ?string $plugin_path = '';
    
    /**
     * Define plugin url
     *
     * @var string|null
     * @since 1.0.0
     */
    public static ?string $plugin_url = '';
    
    /**
     * Prefix plugin
     *
     * @var array
     * @since 1.0.0
     */
    private static array $global_params = [];
    
    /**
     * Initialize the plugin
     *
     * @see getPluginPrefix() to get plugin text domain;
     * @see getPluginDirPath() to get absolut plugin path;
     * @see getPluginDirUrl() to get plugin url;
     * @since 1.0.0
     */
    public function __construct() {
        
        if ( empty( self::$global_params ) ) {
            self::$global_params = [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'home_url' => home_url(),
            ];
        }
        
        /** Define plugin absolute path */
        if ( empty( self::$plugin_path ) ) {
            self::$plugin_path = CNN_PLUGIN_PATH;
        }
        
        /** Define plugin absolute path */
        if ( empty( self::$plugin_url ) ) {
            self::$plugin_url = CNN_PLUGIN_URL;
        }
        
        /** Load plugin text domain */
        add_action( 'plugins_loaded', [ $this, 'loadPluginTextDomain' ] );
        
        /** Load admin global styles and script */
        add_action( 'admin_enqueue_scripts', [ $this, 'loadAdminStylesAndScripts' ] );
        
        /** Load public global styles and script */
        add_action( 'wp_enqueue_scripts', [ $this, 'loadPublicStylesAndScripts' ] );
        
        /** Flush plugin rewrite rules */
        add_action( 'init', [ $this, 'flushRewriteRules' ], 20 );
        
        /** Init plugin post type */
        add_action( 'init', [ $this, 'initPluginPostType' ] );
        
        /** Init plugin shortcode */
        add_action( 'init', [ LotteryShortCode::class, 'getInstance' ] );
        
        /** Post type custom admin columns */
        add_action( 'init', [ LotteryAdminColumns::class, 'getInstance' ] );
    }
    
    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     * @since 1.0.0
     */
    public static function getInstance(): object {
        /** If the single instance hasn't been set, set it now. */
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    /**
     * Load the plugin text domain for translation.
     *
     * @return void
     * @since 1.0.0
     */
    public function loadPluginTextDomain(): void {
        load_plugin_textdomain( self::$prefix, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
    }
    
    /**
     * Setup plugin cpt
     *
     * @return void
     * @since 1.0.0
     */
    public function initPluginPostType(): void {
        $post_type             = new LotteryCPT();
        $post_type->cpt_key    = 'cnn-lottery';
        $post_type->cpt_name   = 'Loterias';
        $post_type->rewrite    = 'loterias';
        $post_type->cpt_args   = [
            'supports'          => [ 'title' ],
            'input_placeholder' => 'Número do concurso',
            'menu_icon'         => 'dashicons-money-alt'
        ];
        $post_type->cpt_labels = [
            'add_new_item' => 'Adicionar novo concurso',
            'add_new'      => 'Nova concurso',
            'all_items'    => 'Concursos'
        ];
        $post_type->makeCpt();
    }
    
    /**
     * Load admin styles and scripts
     *
     * @return void
     * @since 1.0.0
     */
    public function loadAdminStylesAndScripts(): void {
        if ( file_exists( self::$plugin_path . 'admin/assets/css/main.css' ) ) {
            wp_enqueue_style( self::$prefix . self::$admin_css_file, self::$plugin_url . 'admin/assets/css/main.css', [], self::$file_version );
        }
        if ( file_exists( self::$plugin_path . 'admin/assets/js/main.js' ) ) {
            wp_enqueue_script( self::$prefix . self::$admin_js_file, self::$plugin_url . 'admin/assets/js/main.js', [ 'jquery' ], self::$file_version, true );
            wp_localize_script( self::$prefix . self::$admin_js_file, self::$global_params_object_name, self::$global_params );
        }
    }
    
    /**
     * Load styles and scripts
     *
     * @return void
     * @since 1.0.0
     */
    public function loadPublicStylesAndScripts(): void {
        if ( file_exists( self::$plugin_path . 'public/assets/css/main.css' ) ) {
            wp_enqueue_style( self::$prefix . self::$public_css_file, self::$plugin_url . 'public/assets/css/main.css', [], self::$file_version );
        }
        if ( file_exists( self::$plugin_path . 'public/assets/js/main.js' ) ) {
            wp_enqueue_script( self::$prefix . self::$public_js_file, self::$plugin_url . 'public/assets/js/main.js', [ 'jquery' ], self::$file_version, true );
            wp_localize_script( self::$prefix . self::$public_js_file, self::$global_params_object_name, self::$global_params );
        }
    }
    
    /**
     * Return plugin absolute path
     *
     * @return string
     * @since 1.0.0
     */
    public static function getPluginDirPath(): string {
        return self::$plugin_path;
    }
    
    /**
     * Return plugin url
     *
     * @return string
     * @since 1.0.0
     */
    public static function getPluginDirUrl(): string {
        return self::$plugin_url;
    }
    
    /**
     * Get plugin prefix to internationalization
     *
     * @return string
     * @since 1.0.0
     */
    public static function getPluginPrefix(): string {
        return self::$prefix;
    }
    
    /**
     * Flush WordPress rewrite rules
     * @return void
     * @since 1.0.0
     */
    public function flushRewriteRules(): void {
        if ( is_admin() && get_option( 'cnn_flush_rewrite' ) ) {
            /** false positive, detecting theme territory... */
            flush_rewrite_rules();//phpcs:ignore
            delete_option( 'cnn_flush_rewrite' );
        }
    }
    
    /**
     * Plugin activate hook
     *
     * @return void
     * @since 1.0.0
     */
    public static function pluginActivateAction(): void {
        if ( ! get_option( 'cnn_flush_rewrite' ) ) {
            add_option( 'cnn_flush_rewrite', true );
            add_option( 'lottery_create_terms', true );
        }
    }
    
    /**
     * Plugin deactivate hook
     * @return void
     * @since 1.0.0
     */
    public static function pluginUninstallAction(): void {
        /** do something */
    }
}
