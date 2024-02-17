<?php
/*
Plugin Name: Loterias Caixa - CNN
Description: Exibir os resultados das loterias da Caixa Econômica Federal
Version: 1.0
Author: Henrique Oliveira
*/

function ativar_loterias_caixa()
{
}

register_activation_hook(__FILE__, 'ativar_loterias_caixa');


require_once(plugin_dir_path(__FILE__) . 'includes/class-meu-loterias-caixas.php');
require_once(plugin_dir_path(__FILE__) . 'includes/api-handler.php');
require_once(plugin_dir_path(__FILE__) . 'includes/frontend-handler.php');


if (class_exists('Meu_Loterias_Caixas')) {
  $meu_loterias_caixas = new Meu_Loterias_Caixas();
}


function shortcode_meu_plugin_loterias($atts)
{
}
add_shortcode('meu_loterias_caixas', 'shortcode_meu_loterias_caixas');
