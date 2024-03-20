<?php
if (!defined('ABSPATH')) {
    exit();
}

class LoteriaCaixaCpt {

    public function __construct() {
        // Registrar o tipo de postagem personalizado ao instanciar a classe
        add_action('init', array($this, 'lotcaixa_register_lottery_post_type'));
        
        // Adicionar metabox para os metadados
        add_action('add_meta_boxes', array($this, 'lotcaixa_add_lottery_meta_box'));

        // Salvar os valores dos metadados
        add_action('save_post', array($this, 'lotcaixa_save_lottery_meta_data'));
        
        // Adicionar colunas personalizadas na página de administração
        add_filter('manage_loterias_posts_columns', array($this, 'lotcaixa_add_custom_columns'));
        add_action('manage_loterias_posts_custom_column', array($this, 'lotcaixa_populate_custom_columns'), 10, 2);
    }

    public function lotcaixa_register_lottery_post_type() {
   
        $labels = array(
            'name'                => esc_html__( 'Loterias', 'loterias-caixa' ),
            'singular_name'       => esc_html__( 'Loteria', 'loterias-caixa' ),
            'menu_name'           => esc_html__( 'Loteria', 'loterias-caixa' ),
            'name_admin_bar'      => esc_html__( 'Loteria', 'loterias-caixa' ),
            'parent_item_colon'   => esc_html__( 'Parent Item:', 'loterias-caixa' ),
            'all_items'           => esc_html__( 'Todas as Loterias', 'loterias-caixa' ),
            'add_new_item'        => esc_html__( 'Adicionar Nova Loteria', 'loterias-caixa' ),
            'add_new'             => esc_html__( 'Adicionar Nova', 'loterias-caixa' ),
            'new_item'            => esc_html__( 'Nova Loteria', 'loterias-caixa' ),
            'edit_item'           => esc_html__( 'Editar Loteria', 'loterias-caixa' ),
            'update_item'         => esc_html__( 'Atualizar Loteria', 'loterias-caixa' ),
            'view_item'           => esc_html__( 'Ver Loteria', 'loterias-caixa' ),
            'search_items'        => esc_html__( 'Buscar Loteria', 'loterias-caixa' ),
            'not_found'           => esc_html__( 'Não encontrado', 'loterias-caixa' ),
            'not_found_in_trash'  => esc_html__( 'Não encontrado na Lixeira', 'loterias-caixa' ),
        );
    
        $args = array(
            'label' => 'Loteria',
            'public' => true, // Tornando público
            'show_ui' => current_user_can('manage_options'),
            'labels' => $labels,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-tickets-alt' // Ícone mais apropriado
        );
    
        register_post_type('loterias', $args);
    }

    // Adicionar metabox para os metadados
    public function lotcaixa_add_lottery_meta_box() {
        add_meta_box('lottery_meta_box', 'Informações da Loteria', array($this, 'lotcaixa_render_lottery_meta_box'), 'loterias', 'normal', 'default');
    }

    // Renderizar o conteúdo do metabox
    public function lotcaixa_render_lottery_meta_box($post) {
        global $post;
        $post_id = $post->ID;
        wp_nonce_field('lotcaixa_meta_box_nonce', 'lotcaixa_meta_box_nonce');	

        $loteria = get_post_meta($post_id, 'loteria', true);
        $concurso = get_post_meta($post_id, 'concurso', true);
        $data_concurso = get_post_meta($post_id, 'data_concurso', true);        

        echo '<div class="row">';

        echo '<div class="col">';
        echo '<label for="loteria">Loteria:</label><br>';
        echo '<select id="loteria" name="loteria" required >';
        foreach (LOTERIAS_VALIDAS as $loteria_option) {
            echo '<option value="' . esc_attr($loteria_option) . '" ' . selected($loteria, $loteria_option, false) . '>' . esc_html($loteria_option) . '</option>';
        }
        echo '</select><br>';
        echo '</div>';

        echo '<div class="col">';
        echo '<label for="concurso">Concurso:</label><br>';
        echo '<input type="number" id="concurso" name="concurso" value="' . esc_attr($concurso) . '" required /><br>';
        echo '</div>';

        echo '<div class="col">';
        echo '<label for="data_concurso">Data do Concurso:</label><br>';
        echo '<input type="date" id="data_concurso" name="data_concurso" value="' . esc_attr($data_concurso) . '" required />';
        echo '</div>';

        echo '</div>';
    }

    // Salvar os valores dos metadados
    public function lotcaixa_save_lottery_meta_data($post_id) {
        global $post;

        // Checks save status
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);  
        $is_valid_nonce = ( isset($_POST['lotcaixa_meta_box_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['lotcaixa_meta_box_nonce'])), basename(__FILE__)) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }    
        // Salvar os valores dos metadados
        if (isset($_POST['loteria'])) {
            update_post_meta($post_id, 'loteria', sanitize_text_field($_POST['loteria']));
        }
        if (isset($_POST['concurso'])) {
            update_post_meta($post_id, 'concurso', sanitize_text_field($_POST['concurso']));
        }
        if (isset($_POST['data_concurso'])) {
            update_post_meta($post_id, 'data_concurso', sanitize_text_field($_POST['data_concurso']));
        }
    }

    // Adicionar colunas personalizadas na página de administração
    public function lotcaixa_add_custom_columns($columns) {
        $columns['loteria'] = 'Loteria';
        $columns['concurso'] = 'Concurso';
        $columns['data_concurso'] = 'Data do Concurso';
        $columns['code'] = 'Shortcode';
        return $columns;
    }

    // Popule as colunas personalizadas com os valores dos metadados correspondentes
    public function lotcaixa_populate_custom_columns($column, $post_id) {
        switch ($column) {
            case 'loteria':
                echo get_post_meta($post_id, 'loteria', true);
                break;
            case 'concurso':
                echo get_post_meta($post_id, 'concurso', true);
                break;
            case 'data_concurso':
                $data_concurso = get_post_meta($post_id, 'data_concurso', true);
                if ($data_concurso) {
                    $data_concurso_formatada = date('d/m/Y', strtotime($data_concurso));
                    echo esc_html($data_concurso_formatada);
                }
                break;
            case 'code':
                $loteria = get_post_meta($post_id, 'loteria', true);
                $concurso = get_post_meta($post_id, 'concurso', true);
                echo '[loterias loteria="'.$loteria.'" concurso="'.$concurso.'"]';
                break;
        }
    }

}

new LoteriaCaixaCpt();

