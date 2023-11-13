<?php

namespace LoteriasPlugin;

require_once __DIR__ . '/LoteriasPostType.php';
require_once __DIR__ . '/LoteriasTaxonomy.php';
require_once __DIR__ . '/LoteriasCheckPost.php';
require_once __DIR__ . '/LoteriasInsertPost.php';
require_once __DIR__ . '/LoteriasTinyMCE.php';
require_once __DIR__ . '/LoteriasReturnHtml.php';

class LoteriasPlugin {

    protected int $id_post;
    protected string $data_post;
    protected int $default_time_transient = 1800;

    public function __construct() {
        
        add_action('init', [$this, 'init']);
        add_shortcode('loteria', [$this, 'loteriaShortcode']);
        add_action( 'wp_enqueue_scripts', [$this, 'loteriaShortcodeScripts']);

    }

    public function init() {

        $loteriasTinyMCE = new LoteriasTinyMCE();
        $loteriasPostType = new LoteriasPostType();
        $loteriasTaxonomy = new LoteriasTaxonomy();

        $loteriasPostType->register();
        $loteriasTaxonomy->register();
    }

    function loteriaShortcodeScripts() {
        global $post;
        if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'loteria') ) {
            wp_enqueue_style( 'loterias-shortcode-plugin',  plugin_dir_url(__DIR__) . 'assets/css/loterias.css', array(), "1.0", 'all' );
        }
    }

    private function getDataAPI($api_url) {

        $response = wp_remote_get($api_url);

        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);
            return $data;
        } 
        return false;

    }

    public function loteriaShortcode($atts) {

         $checkPost = new LoteriasCheckPost();
         $insertPost = new LoteriasInsertPost();
         $returnHtml = new LoteriasReturnHtml();

         // Definindo os valores padrão para os atributos
         $atts = shortcode_atts(
            [
                'concurso'      => 'latest',
                'tipo_concurso' => 'megasena',
            ],
            $atts        
         );

        // Obtendo os valores dos atributos
        $concurso = $atts['concurso'] === 'ultimo' ? 'latest' : (int) $atts['concurso'];
        $tipo_concurso = $atts['tipo_concurso'];
        $transient_loterias = 'loterias_concurso_'.$tipo_concurso;
        $api_url = 'https://loteriascaixa-api.herokuapp.com/api/'.$tipo_concurso.'/'.$concurso;


        if ($concurso === 'latest') {

            $getTransientLoterias = get_transient($transient_loterias);
            
            if ($getTransientLoterias === false) {
                $getTransientLoterias = $this->getDataAPI($api_url);
                $time_transient = strtotime($getTransientLoterias['dataProximoConcurso']) - strtotime("today");
                set_transient($transient_loterias, json_encode($getTransientLoterias), $time_transient > $this->default_time_transient ? $time_transient : $this->default_time_transient);
                $this->data_post = $checkPost->check($getTransientLoterias) ===  false ? get_post_field('post_content',$insertPost->add($getTransientLoterias)) : json_encode($getTransientLoterias) ;
            
            } else {
                $this->data_post = $getTransientLoterias;
            }
            
        } else {

            $this->id_post = $checkPost->check(array('loteria' => $tipo_concurso, 'concurso' => $concurso));
                
            if ($this->id_post !== false && $this->id_post > 0 ) {
                $this->data_post = get_post_field('post_content',$this->id_post);
            } else {
                $getDataLoterias = $this->getDataAPI($api_url);
                $this->data_post = get_post_field('post_content',$insertPost->add($getDataLoterias));
            }

        }

        return $returnHtml->html($this->data_post);

    }

}