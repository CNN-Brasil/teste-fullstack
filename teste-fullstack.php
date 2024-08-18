<?php

/**
 * Plugin Name: Loterias
 * Description: Um plugin para exibir resultados dos jogos das Loterias Caixa.
 * Version: 1.0
 * Author: Lucas
 */

//Estilo CSS do plugin entra no header
function loterias_enqueue_styles()
{
  wp_enqueue_style('loterias-style', plugin_dir_url(__FILE__) . 'assets/css/loterias-style.css');
}
add_action('wp_enqueue_scripts', 'loterias_enqueue_styles');

if (!defined('ABSPATH')) {
  die("Invalid request");
}

//Classe principal do plugin
class PluginLoteria
{
  public function __construct()
  {
    add_action('init', array($this, 'create_loterias_post_type'));
    add_shortcode('loterias_api', array($this, 'loterias_api_shortcode'));
  }

  //Cria o tipo de post "Loterias"
  function create_loterias_post_type()
  {
    $labels = array(
      'name' => _x('Loterias', 'post type general name', 'text_domain'),
      'singular_name' => _x('Loteria', 'post type singular name', 'text_domain'),
      'menu_name' => _x('Loterias', 'admin menu', 'text_domain'),
      'name_admin_bar' => _x('Loteria', 'add new on admin bar', 'text_domain'),
    );

    $args = array(
      'label' => __('Loteria', 'text_domain'),
      'description' => __('Resultados das Loterias', 'text_domain'),
      'labels' => $labels,
      'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'menu_position' => 3,
      'menu_icon' => 'dashicons-money-alt',
      'has_archive' => true,
      'publicly_queryable' => true,
      'capability_type' => 'post',
    );
    register_post_type('loterias', $args);
  }

  //Shortcode para exibir os resultados das loterias e buscar o template caso exista.
  function loterias_api_shortcode($atts)
  {
    $atts = shortcode_atts(
      array(
        'loteria' => '',
        'concurso' => 'latest',
      ),
      $atts,
      'loterias_api'
    );

    $loteria = sanitize_text_field($atts['loteria']);
    $concurso = sanitize_text_field($atts['concurso']);

    if (empty($concurso)) {
      $concurso = 'latest';
    }

    $post_id = $this->get_loteria_post_id($loteria, $concurso);
    if ($post_id) {
      $post = get_post($post_id);
      if ($post) {
        return apply_filters('the_content', $post->post_content);
      }
    }

    $data = $this->fetch_loteria_data($loteria, $concurso);
    if (!$data) {
      return '<p>Concurso não encontrado.</p>';
    }

    $this->save_loteria_data($loteria, $concurso, $data);

    $template_file = plugin_dir_path(__FILE__) . 'templates/' . $data['loteria'] . '.php';

    if (file_exists($template_file)) {
      ob_start();
      include($template_file);
      return ob_get_clean();
    } else {
      return '<p>Concurso não encontrado.</p>';
    }
  }

  //Busca os dados da API e salva no cache
  private function fetch_loteria_data($loteria, $concurso)
  {
    $cache_key = 'loterias_api_' . $loteria . '_' . $concurso;
    $data = wp_cache_get($cache_key, 'loterias_api');

    if ($data === false) {
      $api_url = 'https://loteriascaixa-api.herokuapp.com/api/' . $loteria . '/' . $concurso;
      $response = wp_remote_get($api_url);
      if (is_wp_error($response)) {
        return false;
      }

      $body = wp_remote_retrieve_body($response);
      $data = json_decode($body, true);
      if (!is_array($data) || empty($data)) {
        return false;
      }

      wp_cache_set($cache_key, $data, 'loterias_api', 3600);
    }

    return $data;
  }

  //Salva os dados da API no banco de dados
  private function save_loteria_data($loteria, $concurso, $data)
  {
    // Mapeamento dos nomes das loterias
    $loteria_mapping = [
      'maismilionaria' => 'Mais Milionária',
      'megasena'       => 'Mega-Sena',
      'lotofacil'      => 'Lotofácil',
      'quina'          => 'Quina',
      'lotomania'      => 'Lotomania',
      'timemania'      => 'Timemania',
      'duplasena'      => 'Dupla Sena',
      'federal'        => 'Loteria Federal',
      'diadesorte'     => 'Dia de Sorte',
      'supersete'      => 'Super Sete',
    ];

    $existing_post_id = $this->get_loteria_post_id($loteria, $concurso);

    if (!$existing_post_id) {
      // Verifica o mapeamento para o nome da loteria
      $loteria_nome = isset($loteria_mapping[$data['loteria']]) ? $loteria_mapping[$data['loteria']] : $data['loteria'];

      // Substitui "lastest" por "último" no título do post
      $concurso_title = $concurso === 'latest' ? 'último' : $concurso;

      $post_data = array(
        'post_title'   => $loteria_nome . ' - Concurso - ' . $concurso_title,
        'post_content' => $this->format_post_content($data),
        'post_status'  => 'publish',
        'post_type'    => 'loterias',
        'meta_input'   => array(
          'loteria'  => $loteria,
          'concurso' => $concurso,
        ),
      );

      wp_insert_post($post_data);
    }
  }

  //Busca o ID do post da loteria
  private function get_loteria_post_id($loteria, $concurso)
  {
    $query = new WP_Query(array(
      'post_type'  => 'loterias',
      'meta_query' => array(
        array(
          'key'   => 'loteria',
          'value' => $loteria,
        ),
        array(
          'key'   => 'concurso',
          'value' => $concurso,
        ),
      ),
    ));

    if ($query->have_posts()) {
      return $query->posts[0]->ID;
    }

    return false;
  }

  //Formata o conteúdo do post pra ficar igual ao template
  private function format_post_content($data)
  {
    $template_file = plugin_dir_path(__FILE__) . 'templates/' . $data['loteria'] . '.php';

    if (file_exists($template_file)) {
      ob_start();
      include($template_file);
      return ob_get_clean();
    } else {
      return '<p>Concurso não encontrado.</p>';
    }
  }

  //Ativa o plugin
  public function activate()
  {
    $this->create_loterias_post_type();
    flush_rewrite_rules();
  }

  //Desativa o plugin
  public function deactivate() {}

  //Desinstala o plugin
  public function uninstall()
  {
    // Argumentos para buscar todos os posts do tipo 'loterias'
    $args = array(
      'post_type' => 'loterias',
      'post_status' => 'any',
      'numberposts' => -1
    );

    // Busca todos os posts do tipo 'loterias'
    $loterias_posts = get_posts($args);

    // Loop através de cada post encontrado e o deleta
    foreach ($loterias_posts as $post) {
      wp_delete_post($post->ID, true); // O segundo parâmetro true força a exclusão permanente
    }

    // Remove as regras de reescrita
    flush_rewrite_rules();
  }
}

//Instancia a classe principal do plugin
if (class_exists('PluginLoteria')) {
  $loterias_plugin = new PluginLoteria();

  //Registra a ativação do plugin 
  register_activation_hook(__FILE__, array($loterias_plugin, 'activate'));

  //Registra a desativação do plugin
  register_deactivation_hook(__FILE__, array($loterias_plugin, 'deactivate'));

  //Registra a desinstalação do plugin
  register_uninstall_hook(__FILE__, array($loterias_plugin, 'uninstall'));
}
