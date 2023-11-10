<?php
/*
Plugin Name: Loterias Plugin
Description: Um plugin para exibir resultados de loterias da caixa, utilizando a API do Guto Alves. (https://github.com/guto-alves/loterias-api)
Version: 1.0
Author: Gregory Lima
*/


defined( constant_name: 'ABSPATH' ) || exit ;

define('LP_PLUGIN_FILE', __FILE__);
define('LP_PLUGIN_PATH', untrailingslashit( plugin_dir_path (file:LP_PLUGIN_FILE)));
define('LP_PLUGIN_URL', untrailingslashit( plugins_url( path: '/', plugin:LP_PLUGIN_FILE)));

require_once LP_PLUGIN_PATH . '/includes/Plugin.php';
require_once LP_PLUGIN_PATH . '/includes/Activate.php';
require_once LP_PLUGIN_PATH . '/includes/Deactivate.php';
require_once LP_PLUGIN_PATH . '/includes/CreateHtml.php';


if (class_exists( class:'Plugin')){
    function LP(){
        return Plugin::getInstance();
    }

    //define('LP_PLUGIN_FILE', __FILE__);
    $plugin = LP();
}

// Ative o plugin
register_activation_hook(__FILE__, array($plugin, 'activate'));

register_deactivation_hook(__FILE__, array($plugin, 'deactivate'));

function registrar_post_personalizado() {
    $args = array(
        'public' => false,
        'show_ui' => true,
        'label' => 'Loterias',
        'supports' => array('title', 'editor'),
        'rewrite' => false,
    );
    register_post_type('loterias', $args);
}

add_action('init', 'registrar_post_personalizado');


// Salve o título como o "Nome-ID único"
function salvar_nome_id_unico($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    $post = get_post($post_id);
    if (!empty($post) && $post->post_type === 'loterias') {
        update_post_meta($post_id, 'nome_id_unico', sanitize_text_field($post->post_title));
    }
}
add_action('save_post', 'salvar_nome_id_unico');


// Salve o valor do campo de conteúdo JSON
function salvar_conteudo_json($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['conteudo_json'])) {
        update_post_meta($post_id, 'conteudo_json', sanitize_text_field($_POST['conteudo_json']));
    }
}
add_action('save_post', 'salvar_conteudo_json');


