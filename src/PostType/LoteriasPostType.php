<?php

/**
 * Internal Loterias
 *
 * @package Loterias
 */

/*
Plugin Name: Loterias
Plugin URI: https://github.com/ctoveloz/teste-fullstack
Description: Loterias Tigrin
Version: 1.0.0
Author: Cristiano Matos
License: MIT
Copyright: Copyright (c) 2024, Cristiano Matos
*/

namespace Cnnbr\TesteFullstack\PostType;

class LoteriasPostType
{
    /**
     * Register actions.
     *
     * @since 1.0.0
     */
    public function register()
    {
        add_action('init', [$this, 'registerPostType']);
    }

    /**
     * Register 'loterias' post type.
     *
     * @since 1.0.0
     */
    public function registerPostType()
    {
        $labels = array(
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'supports' => array('title', 'editor'),
            'has_archive' => true,
        );

        register_post_type('loterias', $args);
    }
}