<?php

namespace LoteriasPlugin;

class LoteriasPostType
{
    /**
     * Constructor that hooks into the WordPress 'init' action to register the custom post type.
     *
     * The constructor ensures that the `register_post_type` function is called when the WordPress 'init' action fires.
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
    }

    /**
     * Registers the custom post type 'loterias' for storing lottery results.
     *
     * This function defines the labels and arguments for the 'loterias' post type, which will store
     * lottery contest data. The post type supports a title, editor, and custom fields, and it is publicly
     * accessible and has an archive page.
     */
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
