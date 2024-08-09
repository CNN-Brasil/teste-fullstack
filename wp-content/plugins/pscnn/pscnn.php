<?php
/**
 * @package PSCNN_Fullstack_Test
 * @version 0.0.1
 */
/*
Plugin Name: Pasquali Solution CNN Fullstack Test
Plugin URI: https://hlsmelo.com.br
Description: This is my first test for a developer position at Pasquali Solution / brazillian CNN.
Author: Henrique Melo
Version: 0.0.1
Author URI: https://hlsmelo.com.br
*/

namespace PSCNN;

use PSCNN\Modules\API;
use PSCNN\Modules\Post_Types;
use PSCNN\Modules\Shortcodes;

class PSCNN {
    const TEXT_DOMAIN = 'pscnn-test';

    /**
     * The PSCNN::add_scripts method adds script dependencies and styles
     *  and is called by global initialization in the wp_enqueue_scripts action
     *
     * @since 0.0.1
     *
     * @return void
     */

    static public function add_scripts(): void {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $version = get_plugin_data(__FILE__)['Version'];
        $path = plugin_dir_url(__FILE__) . 'assets';

        wp_enqueue_style('main', "{$path}/styles/main.min.css", [], false);

        wp_enqueue_script('axios', 'https://unpkg.com/axios/dist/axios.min.js');
        wp_localize_script('axios', 'pscnn', ['apiBaseUrl' => get_rest_url() ]);

        wp_enqueue_script('vue', 'https://br.vuejs.org/js/vue.min.js');
        wp_enqueue_script('currency', 'https://unpkg.com/currency.js@~2.0.0/dist/currency.min.js');

        wp_enqueue_script('main', "{$path}/scripts/main.min.js", ['vue', 'axios'], $version, ['in_footer' => true]);
    }

    /**
     * Method PSCNN::init is called when activating the plugin to take care of its initialization.
     *
     * @since 0.0.1
     *
     *  @return void
     */

    static public function init(): void {
        require_once __DIR__ . '/vendor/autoload.php';

        Post_Types::init();
        Shortcodes::init();
        add_action('wp_enqueue_scripts', self::class . '::add_scripts');
        add_action('rest_api_init', API::class . '::init');
    }
}

add_action('init', PSCNN::class . '::init');
