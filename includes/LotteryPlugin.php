<?php

namespace LotteryChallenge;

/**
 * Class LotteryPlugin
 * @package LotteryChallenge
 * 
 * Plugin principal para manipulação de loterias
 */
class LotteryPlugin
{
    /**
     * Inicializa o plugin
     */
    public static function init()
    {
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
        add_action( 'init', [ __CLASS__, 'register_shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
    }

    /**
     * Registra o custom post type "loterias"
     */
    public static function register_cpt()
    {
        $lottery_cpt = new LotteryCPT();
        $lottery_cpt->register_cpt();
    }

    /**
     * Registra o shortcode "loterias" para renderização de resultados de loterias
     */
    public static function register_shortcode()
    {
        $shortcode_handler = new ShortcodeHandler();
        $shortcode_handler->register_shortcode();
    }
    
    /**
     * Enfileira os scripts e estilos do plugin
     */
    public static function enqueue_scripts()
    {
        wp_enqueue_style( 'lottery-styles', plugin_dir_url( __FILE__ ) . '../assets/css/lottery-styles.css' );
    }

    /**
     * Ativa o plugin
     */
    public static function activate()
    {
        $lottery_cpt = new LotteryCPT();
        $lottery_cpt->register_cpt();
        flush_rewrite_rules();
    }

    public static function deactivate()
    {
        $cache_manager = new CacheManager();
        $lottery_posts = get_posts(array(
            'post_type' => 'loterias',
            'numberposts' => -1
        ));

        foreach ($lottery_posts as $post) {
            $lottery_name = get_post_meta($post->ID, '_lottery_name', true);
            $contest_number = get_post_meta($post->ID, '_lottery_contest', true);
            
            $cache_manager->clear_cache($lottery_name . '_' . $contest_number);
            $cache_manager->clear_cache('timeout_' . $lottery_name . '_' . $contest_number);
            $cache_manager->clear_cache($lottery_name . '_latest');
            $cache_manager->clear_cache('timeout_' . $lottery_name . '_latest');
        }

        unregister_post_type('loterias');
        flush_rewrite_rules();
    }

    public static function uninstall()
    {
        $lottery_posts = get_posts(array(
            'post_type' => 'loterias',
            'numberposts' => -1,
            'post_status' => 'any'
        ));

        foreach ($lottery_posts as $post) {
            delete_post_meta($post->ID, '_lottery_result');
            delete_post_meta($post->ID, '_lottery_contest');
            delete_post_meta($post->ID, '_lottery_name');
            wp_delete_post($post->ID, true);
        }
    }
}