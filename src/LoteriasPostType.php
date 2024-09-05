<?php

namespace LoteriasPlugin;

class LoteriasPostType
{
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
    }

    public function register_post_type()
    {
        $labels = [
            'name' => 'Loterias',
            'singular_name' => 'Concurso',
            'menu_name' => 'Loterias',
            'add_new_item' => 'Adicionar Novo Concurso',
            'edit_item' => 'Editar Concurso',
            'new_item' => 'Novo Concurso',
            'view_item' => 'Ver Concurso',
            'search_items' => 'Buscar Concursos',
            'not_found' => 'Nenhum concurso encontrado',
            'not_found_in_trash' => 'Nenhum Concurso encontrado no lixo',
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'has_archive' => true,
        ];

        register_post_type('loterias', $args);
    }
}
