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

class Activator
{
    const BASE_URL = LOTERIAS_API_URL;

    /**
     * Handles plugin activation.
     *
     * @return void Deactivates the plugin if API is not accessible.
     */
    public static function activate()
    {
        $response = wp_remote_get(self::BASE_URL);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            deactivate_plugins(plugin_basename(__FILE__));

            wp_die(
                'A API da Caixa Econômica Federal não está acessível no momento. O plugin foi desativado. Por favor, verifique a URL da API e tente novamente.',
                'Erro de Ativação',
                array('back_link' => true)
            );
        }

        flush_rewrite_rules();
    }
}
