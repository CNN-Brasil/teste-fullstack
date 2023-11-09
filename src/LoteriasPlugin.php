<?php

namespace LoteriasPlugin;

class LoteriasPlugin {
    public function __construct() {
        add_action('init', [$this, 'registerLoteriasPostType']);
        add_action('init', [$this, 'registerLoteriasTaxonomy']);
        
        add_shortcode('loteria', [$this, 'loteriaShortcode']);
        add_action('admin_init', [$this, 'addLoteriaButton']);

    }

    public function registerLoteriasPostType() {
        $labels = [
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
            'menu_name' => 'Loterias',
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'rewrite' => ['slug' => 'loterias'],
        ];

        register_post_type('loterias', $args);
    }

    public function registerLoteriasTaxonomy() {
        $labels = [
            'name'                       => _x('Concursos', 'taxonomy general name'),
            'singular_name'              => _x('Concurso', 'taxonomy singular name'),
            'search_items'               => __('Buscar Concursos'),
            'all_items'                  => __('Todos os Concursos'),
            'edit_item'                  => __('Editar Consurso'),
            'update_item'                => __('Atualizar Consurso'),
            'add_new_item'               => __('Adicionar novo Consurso'),
            'new_item_name'              => __('Novo Consurso'),
            'menu_name'                  => __('Concursos'),
            'popular_items'              => __('Popular Concursos'),
            'separate_items_with_commas' => null,
            'add_or_remove_items'        => __('Adicionar ou remover concursos'),
            'choose_from_most_used'      => __('Choose from the most used concursos'),
            'not_found'                  => __('Nenhum concurso encontrado'),
            'back_to_items'              => __('Voltar para Concursos'),
        ];

        $args = [
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => false,
            'show_admin_column' => false,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'tipo_concurso'],
            'show_in_rest'      => false,
            'rest_base'         => 'tipo_concurso',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ];

        register_taxonomy('tipo_concurso', ['loterias'], $args);
        
        $tipo_concurso = get_terms( array(
            'taxonomy'   => 'tipo_concurso',
            'hide_empty' => false,
        ) );

        if(count($tipo_concurso) === 0) {
            $this->addDefaultTerms();            
        }

    }

    private function addDefaultTerms() {


        $api_url = 'https://loteriascaixa-api.herokuapp.com/api';
        $response = wp_remote_get($api_url);

        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);

            foreach ($data as $term) {
                wp_insert_term($term, 'tipo_concurso');
            }
        } 
    }

    public function loteriaShortcode($atts) {

         // Definindo os valores padrão para os atributos
         $atts = shortcode_atts(
            [
                'concurso'      => 'last',
                'tipo_concurso' => 'megasena',
            ],
            $atts,
            'loteria'
        );

        // Obtendo os valores dos atributos
        $concurso = $atts['concurso'] === 'ultimo' ? 'last' : (int) $atts['concurso'];
        $tipo_concurso = $atts['tipo_concurso'];

    }

    public function addLoteriaButton() {
        if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
            add_filter('mce_buttons', [$this, 'registerLoteriaButton']);
            add_filter('mce_external_plugins', [$this, 'addLoteriaButtonScript']);
            wp_enqueue_style( 'admin-loteria-plugin',  plugin_dir_url(__DIR__) . 'assets/admin/css/loteria-button.css', array(), "1.0", 'all' );
        }
    }

    public function registerLoteriaButton($buttons) {
        array_push($buttons, 'loteria_button');
        return $buttons;
    }

    public function addLoteriaButtonScript($plugin_array) {
        $plugin_array['loteria_button'] = plugin_dir_url(__DIR__) . 'assets/admin/js/loteria-button.js';
        return $plugin_array;
    }

}