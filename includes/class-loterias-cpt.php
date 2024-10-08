<?php
class Loterias_CPT {
    public function __construct() {
        add_action('init', array($this, 'register_cpt'));
    }

    public function register_cpt() {
        $labels = array(
            'name'               => 'Loterias',
            'singular_name'      => 'Loteria',
            'menu_name'          => 'Loterias',
            'name_admin_bar'     => 'Loteria',
            'add_new'            => 'Adicionar Novo',
            'add_new_item'       => 'Adicionar Nova Loteria',
            'new_item'           => 'Nova Loteria',
            'edit_item'          => 'Editar Loteria',
            'view_item'          => 'Ver Loteria',
            'all_items'          => 'Todas as Loterias',
            'search_items'       => 'Buscar Loterias',
            'not_found'          => 'Nenhuma loteria encontrada',
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'supports'           => array('title', 'custom-fields'),
        );

        register_post_type('loterias', $args);
    }
}
