<?php
/*
Plugin Name: Plugin de Loterias
Plugin URI: https://planet1.com.br
Description: Realizando o teste para a vaga de.
Version: 1.0
Author: Seu Nome
Author URI: https://planet1.com.br
License: GPLv2 or later
*/
// Autoload function
/********************************************************/

function meu_plugin_enqueue_styles() {
    // Registrar e enfileirar o CSS
    wp_enqueue_style('meu-plugin-estilo', plugin_dir_url(__FILE__) . 'css/estilo.css');
}
add_action('wp_enqueue_scripts', 'meu_plugin_enqueue_styles');


require_once "autoload.php";

use loterias\classes\Loteria;
/********etapa 1***********************************************/
/********Registrando o custom post type************************/
defined('ABSPATH') or die('No script access allowed.');

// Criar o post type "Loterias" ao ativar o plugin
function lm_register_post_type() {
    register_post_type('loterias',
        array(
            'labels'      => array(
                'name'          => __('Loterias'),
                'singular_name' => __('Loteria'),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => array('title', 'editor'),
        )
    );
}
add_action('init', 'lm_register_post_type');
/*******************************************************/







/******etapa 2**************************************************/
/******Criando o shortcode***[lottery_results]******************/
function lm_lottery_results_shortcode($atts) {
    $x = new Loteria();

    $atts = shortcode_atts(
        array(
            'concurso' => 'ultimo',
            'loteria'  => '0'
        ), 
        $atts, 
        'lottery_results'
    );

    $concurso = sanitize_text_field($atts['concurso']);
    $loteria = sanitize_text_field($atts['loteria']);
    $results = '';

      
        

    /***Se algum concurso for diferente de ultimo, eu seto o atributo de classe**/
    if($atts['concurso']!="ultimo") {$x->concurso = $atts['concurso'];}

    if($atts['loteria']!=0){ $x->loteria = $atts['loteria']; } else{echo "loteria não informada";}
        $dados = $x->AcessaApi()->dados;
        echo $dados;

}
add_shortcode('lottery_results', 'lm_lottery_results_shortcode');
/********************************************************/



