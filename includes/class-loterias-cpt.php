<?php

class Loterias_CPT {
    public static function register() {
        $labels = array(
            'name' => _x('Loterias', 'post type general name', 'loterias-plugin'),
            'singular_name' => _x('Loteria', 'post type singular name', 'loterias-plugin'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
        );
        
        register_post_type('loterias', $args);
    }
}
