<?php
/**
 * Plugin Name: Loterias Caixa
 * Plugin URI: https://github.com/Armandomateus41
 * Description: Exibe os resultados dos jogos das Loterias Caixa.
 * Version: 1.0.0
 * Author: Armando Mateus Capita
 * Author URI: https://github.com/Armandomateus41
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define o caminho do plugin
define( 'LOTERIAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Carrega a classe da API
require_once LOTERIAS_PLUGIN_DIR . 'includes/class-loterias-api.php';

// Registra o Custom Post Type 'loterias'
function loterias_register_cpt() {
    $labels = array(
        'name' => __( 'Loterias', 'loterias-resultados' ),
        'singular_name' => __( 'Loteria', 'loterias-resultados' ),
        'menu_name' => __( 'Loterias', 'loterias-resultados' ),
        'add_new_item' => __( 'Adicionar Novo Resultado', 'loterias-resultados' ),
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => true,
        'supports' => array( 'title', 'editor', 'custom-fields' ),
        'rewrite' => array( 'slug' => 'loterias' ),
    );
    
    register_post_type( 'loterias', $args );
}
add_action( 'init', 'loterias_register_cpt' );

// Função para buscar os resultados da API e salvar no CPT
function loterias_store_api_results() {
    $jogos = array( 'megasena', 'quina', 'lotofacil', 'lotomania', 'timemania', 'duplasena' );
    $api = new Loterias_API();

    foreach ( $jogos as $jogo ) {
        $resultado = $api->get_ultimo_concurso( $jogo );

        if ( $resultado ) {
            // Verifica se o concurso já está salvo
            $query = new WP_Query( array(
                'post_type' => 'loterias',
                'meta_query' => array(
                    array(
                        'key' => '_loteria_concurso',
                        'value' => $resultado['concurso'],
                    ),
                    array(
                        'key' => '_loteria_jogo',
                        'value' => $jogo,
                    ),
                ),
            ));

            if ( ! $query->have_posts() ) {
                // Adiciona o prêmio estimado, se disponível
                $premioEstimado = isset( $resultado['valorEstimadoProximoConcurso'] ) ? number_format( $resultado['valorEstimadoProximoConcurso'], 2, ',', '.' ) : 'N/A';

                // Formatar o conteúdo do post com a tabela HTML
                $content = '<h3>Concurso ' . esc_html( $resultado['concurso'] ) . ' - ' . esc_html( $resultado['data'] ) . '</h3>';
                $content .= '<div class="numeros-sorteados">';
                foreach ( $resultado['dezenas'] as $numero ) {
                    $content .= '<div class="numero">' . esc_html( $numero ) . '</div>';
                }
                $content .= '</div>';
               
                $content .= '<h4>Prêmio Estimado para o Próximo Concurso</h4>';
                $content .= '<p>R$ ' . $premioEstimado . '</p>';  // Prêmio estimado
                $content .= '<h4>Premiações</h4>';
                $content .= '<table class="premiacoes-table">';
                $content .= '<thead><tr><th>Faixa</th><th>Ganhadores</th><th>Prêmio</th></tr></thead>';
                $content .= '<tbody>';
                foreach ( $resultado['premiacoes'] as $premio ) {
                    $content .= '<tr>';
                    $content .= '<td>' . esc_html( $premio['faixa'] ) . '</td>';
                    $content .= '<td>' . esc_html( $premio['ganhadores'] ) . '</td>';
                    $content .= '<td>R$ ' . number_format( $premio['valorPremio'], 2, ',', '.' ) . '</td>';
                    $content .= '</tr>';
                }
                $content .= '</tbody></table>';

                // Insere um novo post no CPT 'loterias' com o conteúdo formatado
                wp_insert_post( array(
                    'post_title' => ucfirst( $jogo ) . ' - Concurso ' . $resultado['concurso'],
                    'post_type' => 'loterias',
                    'post_status' => 'publish',
                    'meta_input' => array(
                        '_loteria_jogo' => $jogo,
                        '_loteria_concurso' => $resultado['concurso'],
                    ),
                    'post_content' => $content, // Aqui salva a tabela HTML no conteúdo do post
                ));
            }
        }
    }
}

add_action( 'init', 'loterias_store_api_results' );

// Função para exibir o layout e os botões dos jogos
function loterias_display_shortcode() {
    ob_start();
    include LOTERIAS_PLUGIN_DIR . 'templates/master-template.php';
    return ob_get_clean();
}
add_shortcode( 'loterias_master', 'loterias_display_shortcode' );

// Carrega os arquivos CSS e JavaScript
function loterias_enqueue_assets() {
    wp_enqueue_style( 'loterias-style', plugins_url( '/assets/style.css?v=154', __FILE__ ) );
    wp_enqueue_script( 'loterias-script', plugins_url( '/assets/script.js?v=4123', __FILE__ ), array( 'jquery' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'loterias_enqueue_assets' );