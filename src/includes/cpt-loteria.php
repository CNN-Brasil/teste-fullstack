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
            'menu_icon' => esc_url(LOTCAIXA_URL).'src/assets/images/icon-caixa-25.png',
        );
    
        register_post_type('loterias', $args);
    }

    // Adicionar metabox para os metadados
    public function lotcaixa_add_lottery_meta_box() {
        add_meta_box('lottery_meta_box', 'Informações da Loteria', array($this, 'lotcaixa_render_lottery_meta_box'), 'loterias', 'normal', 'default');
    }

    // Renderizar o conteúdo do metabox
    public function lotcaixa_render_lottery_meta_box($post) {
        $post_id = $post->ID;  
        $loteria = get_post_meta($post_id, 'loteria', true);
        $concurso = get_post_meta($post_id, 'concurso', true);
        $data_concurso = get_post_meta($post_id, 'data_concurso', true);  
        $valor_estimado = get_post_meta($post_id, 'valor_estimado', true);  
        $dezenas = get_post_meta($post_id, 'dezenas', true);
        $premiacoes = get_post_meta($post_id, 'premiacoes', true);   
    
        if ($post->post_status == 'publish') {           
            ?>
            <div id="loteria">
                <div class="loteria-row">
                    <p><?php esc_html_e( 'Loteria', 'loterias-caixa' ); ?>: <strong><?php echo esc_html($loteria); ?></strong></p>
                    <p><?php esc_html_e( 'Concurso', 'loterias-caixa' ); ?>: <strong><?php echo esc_html($concurso); ?></strong></p>
                    <p><?php esc_html_e( 'Dada do concurso', 'loterias-caixa' ); ?>: <strong><?php echo esc_html($data_concurso); ?></strong></p>
                    <p><?php esc_html_e( 'Prêmio estimado', 'loterias-caixa' ); ?>: <strong>R$ <?php echo esc_html($valor_estimado); ?></strong></p>
                </div>
                <div class="loteria-row">
                    <?php
                    if(!empty($dezenas)){  
                        echo "<h3>Números Sorteados</h3>";
                        echo '<ul id="numeros">';
                        foreach ( $dezenas as $dezena ) {
                            echo '<li>'.esc_html($dezena).'</li>';
                        }
                        echo '</ul>';            
                    } 
                    ?>
                </div>
                <div class="loteria-row">
                    <?php 
                    if(!empty($premiacoes)){
                        echo "<h3>Premiações</h3>";
                        echo '<table id="table-premiacoes">';
                        echo '<tr><th>Faixas</th><th>Ganhadores</th><th>Prêmio</th></tr>';
                        foreach ( $premiacoes as $premiacao => $value ) {
                            echo '<tr><td>' . esc_html($value['descricao']) . '</td>';
                            echo '<td>' . esc_html($value['ganhadores']) . '</td>';
                            echo '<td>' . esc_html($value['valorPremio']) . '</td></tr>';
                        }
                        echo '</table>';
                    }
                    ?>               
                </div>
            </div>
            <?php
        } else {
            echo '<p class="error">';
            esc_html_e('Não é recomendado criar uma loteria por aqui, recomendamos que faça uso do nosso shortcode em alguma página do seu site.', 'loterias-caixa');
            echo ' ' . esc_html__('Em caso de dúvidas, favor verificar nossa documentação no link abaixo', 'loterias-caixa') . '</p>';
            echo '<p><a href="' . esc_url(admin_url('edit.php?post_type=loterias&page=lotcaixa_documentation_page')) . '">' . esc_html__('clicando aqui', 'loterias-caixa') . '</a></p>';            
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
                echo esc_html( get_post_meta($post_id, 'loteria', true) );
                break;
            case 'concurso':
                echo esc_html( get_post_meta($post_id, 'concurso', true) );
                break;
            case 'data_concurso':  
                    echo esc_html(get_post_meta($post_id, 'data_concurso', true));                
                break;
            case 'code':
                $loteria = get_post_meta($post_id, 'loteria', true);
                $concurso = get_post_meta($post_id, 'concurso', true);
                $code = '[loterias loteria="'.$loteria.'" concurso="'.$concurso.'"]';
                echo '<input type="text" class="cpyvalue" value=\''.esc_attr($code).'\' readonly onclick="lotcaixaCopyThisValue(this)">';
				echo '<div class="response-copy"></div>';                
                break;
        }
    }

}

new LoteriaCaixaCpt();

