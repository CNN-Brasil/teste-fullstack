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

class Deactivator
{
    /**
     * Fired during plugin deactivation.
     *
     * @since      1.0.0
     * @package    Loterias
     * @author     Cristiano Matos <https://github.com/ctoveloz>
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
