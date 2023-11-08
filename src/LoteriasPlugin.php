<?php
// wp-content/plugins/loterias-plugin/src/LoteriasPlugin.php

namespace LoteriasPlugin;

class LoteriasPlugin {
    public function __construct() {
        add_action('init', [$this, 'registerLoteriasPostType']);
    }

    public function registerLoteriasPostType() {
        $labels = [
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
            'menu_name' => 'Loterias',
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'loterias'],
        ];

        register_post_type('loterias', $args);
    }
}