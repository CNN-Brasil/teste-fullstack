<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasTaxonomy - Responsável por registrar a taxonomia 'tipo_concurso'.
 */
class LoteriasTaxonomy {
    
    /**
     * Registra a taxonomia 'tipo_concurso'.
     */
    public function register() {
        // Rótulos para a taxonomia
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

        // Argumentos para o registro da taxonomia
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

        // Registra a taxonomia 'tipo_concurso'
        register_taxonomy('tipo_concurso', ['loterias'], $args);
        
        // Obtém os termos existentes na taxonomia 'tipo_concurso'
        $tipo_concurso = get_terms( array(
            'taxonomy'   => 'tipo_concurso',
            'hide_empty' => false,
        ) );

        // Adiciona termos padrão se a taxonomia estiver vazia
        if(count($tipo_concurso) === 0) {
            $this->addDefaultTerms();            
        }
    }

    /**
     * Adiciona termos padrão à taxonomia 'tipo_concurso'.
     */
    private function addDefaultTerms() {
        // URL da API para obter termos padrão
        $api_url = 'https://loteriascaixa-api.herokuapp.com/api';
        $response = wp_remote_get($api_url);

        // Verifica se a resposta da API é bem-sucedida
        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            // Decodifica os dados JSON da resposta
            $data = json_decode($response['body'], true);

            // Insere os termos na taxonomia 'tipo_concurso'
            foreach ($data as $term) {
                wp_insert_term($term, 'tipo_concurso');
            }
        } 
    }
}