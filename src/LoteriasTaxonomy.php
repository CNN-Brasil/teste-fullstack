<?php
namespace LoteriasPlugin;

class LoteriasTaxonomy {
    public function register() {
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
            'show_ui'           => true,
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
}
