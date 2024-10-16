<?php
class Loterias_Manager {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
    }

    public function register_post_type() {
        register_post_type('loterias', [
            'labels' => [
                'name' => __('Loterias', 'loterias-caixa'),
                'singular_name' => __('Loteria', 'loterias-caixa')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor'],
            'rewrite' => ['slug' => 'loterias'],
        ]);
    }
}