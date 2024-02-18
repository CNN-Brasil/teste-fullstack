<?php
/*
Plugin Name: Loterias Caixa - CNN
Description: Exibir os resultados das loterias da Caixa Econômica Federal
Version: 1.0
Author: Henrique Oliveira
*/

// Evitar que o arquivo seja acessado diretamente
if (!defined('ABSPATH')) {
  exit; // Sai se o WordPress não estiver carregado
}

function ativar_loterias_caixa()
{
}

register_activation_hook(__FILE__, 'ativar_loterias_caixa');

require_once(plugin_dir_path(__FILE__) . 'includes/class-loterias-shortcode.php');
require_once(plugin_dir_path(__FILE__) . 'includes/class-loterias-api.php');
require_once(plugin_dir_path(__FILE__) . 'includes/class-loterias-post-type.php');


new Loterias_Post_Type();


new Loterias_Shortcode();


add_action('init', 'loterias_activate_cache');

function loterias_activate_cache()
{
  wp_cache_add_global_groups(array('loterias'));
}


function loterias_load_textdomain()
{
  load_plugin_textdomain('loterias', false, plugin_dir_path(__FILE__) . 'languages');
}

add_action('plugins_loaded', 'loterias_load_textdomain');
