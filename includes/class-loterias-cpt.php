<?php
class Loterias_CPT {

    public function __construct() {
        add_action('init', array($this, 'register_cpt'));
        add_filter('manage_loterias_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_loterias_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
    }

    public function register_cpt() {
        $labels = array(
            'name'               => 'Loterias',
            'singular_name'      => 'Loteria',
            'menu_name'          => 'Loterias',
            'name_admin_bar'     => 'Loteria',
            'add_new'            => 'Adicionar Nova',
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
            'supports'           => array('title'),
        );

        register_post_type('loterias', $args);
    }

    // Adicionar colunas personalizadas
    public function set_custom_columns($columns) {
        unset($columns['date']); // Remove a coluna de data padrão

        $columns['loteria'] = 'Loteria';
        $columns['concurso'] = 'Concurso';
        $columns['data'] = 'Data do Concurso';
        $columns['dezenasOrdemSorteio'] = 'Dezenas Sorteadas';

        return $columns;
    }

    // Preencher o conteúdo das colunas personalizadas
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'loteria':
                echo esc_html(get_post_meta($post_id, 'loteria', true));
                break;

            case 'concurso':
                echo esc_html(get_post_meta($post_id, 'concurso', true));
                break;

            case 'data':
                echo esc_html(get_post_meta($post_id, 'data', true));
                break;

            case 'dezenasOrdemSorteio':
                echo esc_html(get_post_meta($post_id, 'dezenasOrdemSorteio', true));
                break;
        }
    }
}

new Loterias_CPT();
