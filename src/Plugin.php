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

namespace Cnnbr\TesteFullstack;

use Cnnbr\TesteFullstack\PostType\LoteriasPostType;
use Cnnbr\TesteFullstack\Shortcode\LoteriaShortcode;

class Plugin
{
    /**
     * Runs the plugin.
     *
     * @since 1.0.0
     */
    public function run()
    {
        // Register post type
        $loterias_post_type = new LoteriasPostType();
        $loterias_post_type->register();

        // Register shortcode
        $loteria_shortcode = new LoteriaShortcode();
        $loteria_shortcode->register();
    }
}
