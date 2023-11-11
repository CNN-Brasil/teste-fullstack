<?php
namespace LoteriasPlugin;

class LoteriasPostType {
    public function register() {
        $labels = [
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
            'menu_name' => 'Loterias',
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'rewrite' => ['slug' => 'loterias'],
        ];

        register_post_type('loterias', $args);
    }
}