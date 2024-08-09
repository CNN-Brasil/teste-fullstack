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
       //echo "<br><hr><hr>"; 
          print_r($dados);
      // echo "<br><hr>"; 
         
    //exit();
    if ($concurso === 'ultimo') {
        $results = lm_fetch_lottery_results();
    } else {
        $results = lm_get_lottery_results($concurso);
        if (!$results) {
            $results = lm_fetch_lottery_results($concurso);
        }
    }

    return $results;
}
add_shortcode('lottery_results', 'lm_lottery_results_shortcode');
/********************************************************/



































/*********etapa 3************************************************/
function lm_fetch_lottery_results($concurso = null) {
    $api_url = 'https://api.loterias.com.br/concursos'; // Substitua pela URL da API real
    if ($concurso) {
        $api_url .= '?concurso=' . $concurso;
    } else {
        $api_url .= '?concurso=ultimo';
    }

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return 'Erro ao buscar resultados.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($concurso) {
        $post_data = array(
            'post_title'   => 'Concurso ' . $concurso,
            'post_content' => json_encode($data),
            'post_status'  => 'publish',
            'post_type'    => 'loterias',
        );
        wp_insert_post($post_data);
    }
    return lm_display_results($data);
}
/*********************************************************/








/*******etapa 4**************************************************/
// Obter resultados do post type
function lm_get_lottery_results($concurso) {
    $query = new WP_Query(array(
        'post_type'  => 'loterias',
        'meta_key'   => 'concurso_number',
        'meta_value' => $concurso,
    ));

    if ($query->have_posts()) {
        $query->the_post();
        return get_the_content();
    }

    return false;
}
/*********************************************************/



/*******etapa 5**************************************************/
// Exibir resultados
function lm_display_results($data) {
    ob_start();
    ?>
    <div class="lottery-results">
        <h2>Resultados</h2>
        <!-- Personalize o layout conforme necessário -->
        <pre><?php print_r($data); ?></pre>
    </div>
    <?php
    return ob_get_clean();
}
/*********************************************************/