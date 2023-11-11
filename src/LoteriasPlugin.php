<?php

namespace LoteriasPlugin;

require_once __DIR__ . '/LoteriasPostType.php';
require_once __DIR__ . '/LoteriasTaxonomy.php';
require_once __DIR__ . '/LoteriasCheckPost.php';
require_once __DIR__ . '/LoteriasInsertPost.php';
require_once __DIR__ . '/LoteriasTinyMCE.php';

class LoteriasPlugin {

    protected int $id_post;
    protected string $data_post;

    public function __construct() {
        
        add_action('init', [$this, 'init']);
        add_shortcode('loteria', [$this, 'loteriaShortcode']);

    }

    public function init() {

        $loteriasTinyMCE = new LoteriasTinyMCE();
        $loteriasPostType = new LoteriasPostType();
        $loteriasTaxonomy = new LoteriasTaxonomy();

        $loteriasPostType->register();
        $loteriasTaxonomy->register();
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

         // Definindo os valores padrão para os atributos
         $atts = shortcode_atts(
            [
                'concurso'      => 'latest',
                'tipo_concurso' => 'megasena',
            ],
            $atts,
            'loteria'
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
                $time_transient = strtotime($getTransientLoterias['dataProximoConcurso']) - strtotime($getTransientLoterias['data']);
                set_transient($transient_loterias, json_encode($getTransientLoterias), $time_transient);
                $this->data_post = $checkPost->check($getTransientLoterias) ===  false ? get_post_field('post_content',$insertPost->add($getTransientLoterias)) : $getTransientLoterias ;
            
            } else {
                
                return $getTransientLoterias;
                
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

        return $this->data_post;

    }

}