<?php

class Loterias_CPT {
        // Método para registrar o post-type 'Loterias'
        public static function register() {
            $labels = array(
                'name' => _x('Loterias', 'post type general name', 'loterias-plugin'),
                'singular_name' => _x('Loteria', 'post type singular name', 'loterias-plugin'),
                'menu_name' => _x('Loterias', 'admin menu', 'loterias-plugin'),
                'name_admin_bar' => _x('Loteria', 'add new on admin bar', 'loterias-plugin'),
                'add_new' => _x('Add New', 'loteria', 'loterias-plugin'),
                'add_new_item' => __('Add New Loteria', 'loterias-plugin'),
                'new_item' => __('New Loteria', 'loterias-plugin'),
                'edit_item' => __('Edit Loteria', 'loterias-plugin'),
                'view_item' => __('View Loteria', 'loterias-plugin'),
                'all_items' => __('All Loterias', 'loterias-plugin'),
                'search_items' => __('Search Loterias', 'loterias-plugin'),
                'parent_item_colon' => __('Parent Loterias:', 'loterias-plugin'),
                'not_found' => __('No loterias found.', 'loterias-plugin'),
                'not_found_in_trash' => __('No loterias found in Trash.', 'loterias-plugin'),
            );

            add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
            
            $args = array(
                'labels' => $labels,
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'editor', 'custom-fields'),
                'show_in_rest' => true, // Suporte REST API
            );
            
            register_post_type('loterias', $args);
        }
    
        // Método para salvar resultados no post-type
        public static function save_results($loteria, $concurso, $results) {
            // Verifica se o concurso já está registrado
            $existing_post = self::get_loteria_post($loteria, $concurso);
    
            if ($existing_post) {
                return; // Já existe um post para este concurso
            }
    
            // Cria um novo post com os resultados
            $post_id = wp_insert_post(array(
                'post_title' => ucfirst($loteria) . ' - Concurso ' . $concurso,
                'post_content' => self::format_results_table($results),
                'post_status' => 'publish',
                'post_type' => 'loterias',
                'meta_input' => array(
                    'loteria' => $loteria,
                    'concurso' => $concurso,
                    'resultados' => $results
                ),
            ));
    
            if (!is_wp_error($post_id)) {
                // Sucesso ao criar o post
                return $post_id;
            }
    
            return false; // Falha ao criar o post
        }
    
        // Método auxiliar para verificar se o concurso já está registrado no post-type
        public static function get_loteria_post($loteria, $concurso) {
            $args = array(
                'post_type' => 'loterias',
                'meta_query' => array(
                    array(
                        'key' => 'loteria',
                        'value' => $loteria,
                        'compare' => '='
                    ),
                    array(
                        'key' => 'concurso',
                        'value' => $concurso,
                        'compare' => '='
                    )
                ),
                'posts_per_page' => 1,
            );
    
            $query = new WP_Query($args);
    
            if ($query->have_posts()) {
                return $query->posts[0];
            }
    
            return false;
        }

        public static function enqueue_assets() {
            // Enfileirar o CSS do Bootstrap
            wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
            // Enfileirar o JS do Bootstrap (opcional, caso precise de funcionalidades JavaScript do Bootstrap)
            wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
        }
    
        // Método para formatar os resultados em uma tabela Bootstrap
        public static function format_results_table($results) {
            var_dump($results);
            $html = '<div class="grid text-center">';
            $html .= '<div class="g-col-12" style="background-color:#298C5F;width:903px;height:68px;
">Concurso: ' . $results['concurso'] . ' - '. $results['data'].'</div>';
            $html .= '<div class="container text-center">';
            $html  .= '<div class="row">';
            foreach ($results['dezenasOrdemSorteio'] as $key => $dezenasOrdemSorteio) {
               if($key == 0){
                 $html .= '<div class="col rounded-circle align-text-bottom" style="background-color:#298C5F;height:67px;width:67px">'. $dezenasOrdemSorteio .'</div>';
               }
               if($key == 1){
                $html .= '<div class="col rounded-circle align-text-bottom" style="background-color:#298C5F;height:67px;width:67px">'. $dezenasOrdemSorteio .'</div>';
              }
              if($key == 2){
                $html .= '<div class="col rounded-circle align-text-bottom" style="background-color:#298C5F;height:67px;width:67px">'. $dezenasOrdemSorteio .'</div>';
              }
              if($key == 3){
                $html .= '<div class="col rounded-circle align-text-bottom" style="background-color:#298C5F;height:67px;width:67px">'. $dezenasOrdemSorteio .'</div>';
              }
              if($key == 4){
                $html .= '<div class="col rounded-circle align-text-bottom" style="background-color:#298C5F;height:67px;width:67px">'. $dezenasOrdemSorteio .'</div>';
              }
              if($key == 5){
                $html .= '<div class="col rounded-circle align-text-bottom" style="background-color:#298C5F;height:67px;width:67px">'. $dezenasOrdemSorteio .'</div>';
              }
           } 
            $html .= '</div>';
            $html .= '</div>';
            
            $html .= '<div class="g-col-12">PRÊMIO</div></div>';
            $html .= '<div class="g-col-12" style="background-color:#298C5F;width:903px;height:68px;
">' . $results['valorArrecadado'] .'</div>';

            foreach ($results['premiacoes'] as $premio) {
                $html .= '<p>' . $premio['descricao'] . ': ' . $premio['valorPremio'] . '</p>';
            }
            $html .= '</div>';
            $html .= '</div>';
    
            return $html;
        }
}
