<?php

namespace LotteryChallenge;

/**
 * Class LotteryCPT
 * @package LotteryChallenge
 * 
 * Manipulador de custom post type para loterias
 */
class LotteryCPT
{
    /**
     * Registra o custom post type "loterias"
     */
    public function register_cpt()
    {
        $labels = [
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
            'menu_name' => 'Loterias',
            'name_admin_bar' => 'Loteria',
            'add_new' => 'Adicionar Nova',
            'add_new_item' => 'Adicionar Nova Loteria',
            'new_item' => 'Nova Loteria',
            'edit_item' => 'Editar Loteria',
            'view_item' => 'Ver Loteria',
            'all_items' => 'Todas as Loterias',
            'search_items' => 'Buscar Loterias',
            'not_found' => 'Nenhuma Loteria encontrada',
            'not_found_in_trash' => 'Nenhuma Loteria encontrada no lixo'
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'loterias'],
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => true,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => false, 
            ),
        ];

        register_post_type('loterias', $args);
    }
}