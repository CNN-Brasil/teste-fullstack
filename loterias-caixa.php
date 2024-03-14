<?php
/**
 * Plugin Name: Loterias Caixa
 * Plugin URI:  http://www.aldodeveloper.com.br
 * Description: Exibe os resultados das Loterias da Caixa via shortcode.
 * Version: 1.0
 * Author: Aldo Oliveira
 * Author URI: http://www.aldodeveloper.com.br
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: loterias-caixa
 *
 * @package LoteriasCaixa
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/vendor/autoload.php';

new \Cnnbr\TesteFullstack\includes\Loterias_CPT();
new \Cnnbr\TesteFullstack\includes\Loterias_Shortcode();

	/**
	 * Ativa o plugin.
	 */
function mp_loterias_ativar() {
    \Cnnbr\TesteFullstack\includes\Loterias_CPT::registrar_cpt_loterias();
    flush_rewrite_rules();
}
    
register_activation_hook(__FILE__, 'mp_loterias_ativar');

	/**
	 * Desativa o plugin.
	 */
function mp_loterias_desativa() {
    $posts = get_posts(array(
        'post_type' => 'loterias',
        'numberposts' => -1,
        'post_status' => 'any'
    ));

    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }

    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'mp_loterias_desativa');

	/**
	 * Enfileira os estilos do plugin.
	 */
function mp_loterias_enqueue_styles() {
    wp_enqueue_style('mp-loterias-styles', plugins_url('/src/assets/css/style.css', __FILE__), array(), '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'mp_loterias_enqueue_styles');