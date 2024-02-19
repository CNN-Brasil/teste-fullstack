<?php

/*
 * Plugin Name:       Loteria
 * Plugin URI:        https://www.cnnbrasil.com.br/
 * Description:       Consulta os resultados dos concursos da loteria
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Marcello Ruoppolo
 * Author URI:        https://www.cnnbrasil.com.br/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       loteria
 * Domain Path:       /lang
 */

class Loteria
{
  private static $instance;

  private function __construct() {
    add_action( 'init', array( $this,'loterias_post_type' ), 0 );
    add_shortcode( 'loterias', array( $this, 'loteria_shortcode' ) );
    add_action( 'add_meta_boxes', array( $this, 'loteria_add_concurso_metabox' ) );
    wp_enqueue_style( 'loteria', plugins_url('assets/css/loteria_style.css', __FILE__), array(), '1.0.0', 'all' );
    wp_register_style('loteria_font', '//fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lora&family=Open+Sans:wght@300;400&display=swap');
    wp_enqueue_style('loteria_font');
  }

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function loterias_post_type() {
    $labels = array(
      'name'                  => _x( 'Loterias', 'Post Type General Name', 'loteria' ),
      'singular_name'         => _x( 'Loteria', 'Post Type Singular Name', 'loteria' ),
      'menu_name'             => __( 'Loterias', 'loteria' ),
      'name_admin_bar'        => __( 'Loterias', 'loteria' ),
      'archives'              => __( 'Item Archives', 'loteria' ),
      'attributes'            => __( 'Item Attributes', 'loteria' ),
      'parent_item_colon'     => __( 'Parent Item:', 'loteria' ),
      'all_items'             => __( 'All Items', 'loteria' ),
      'add_new_item'          => __( 'Add New Item', 'loteria' ),
      'add_new'               => __( 'Add New', 'loteria' ),
      'new_item'              => __( 'New Item', 'loteria' ),
      'edit_item'             => __( 'Edit Item', 'loteria' ),
      'update_item'           => __( 'Update Item', 'loteria' ),
      'view_item'             => __( 'View Item', 'loteria' ),
      'view_items'            => __( 'View Items', 'loteria' ),
      'search_items'          => __( 'Search Item', 'loteria' ),
      'not_found'             => __( 'Not found', 'loteria' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'loteria' ),
      'featured_image'        => __( 'Featured Image', 'loteria' ),
      'set_featured_image'    => __( 'Set featured image', 'loteria' ),
      'remove_featured_image' => __( 'Remove featured image', 'loteria' ),
      'use_featured_image'    => __( 'Use as featured image', 'loteria' ),
      'insert_into_item'      => __( 'Insert into item', 'loteria' ),
      'uploaded_to_this_item' => __( 'Uploaded to this item', 'loteria' ),
      'items_list'            => __( 'Items list', 'loteria' ),
      'items_list_navigation' => __( 'Items list navigation', 'loteria' ),
      'filter_items_list'     => __( 'Filter items list', 'loteria' ),
    );
    $args = array(
      'label'                 => __( 'Loteria', 'loteria' ),
      'description'           => __( 'Resultados das loterias', 'loteria' ),
      'labels'                => $labels,
      'supports'              => array( 'title' ),
      'taxonomies'            => array( 'post_tag' ),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-money-alt',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'post',
      'show_in_rest'          => false,
    );
    register_post_type( 'loteria', $args );

  }

  public function loteria_add_concurso_metabox() {
      add_meta_box(
        'loteria_box_id',
        'Resultados do Concurso',
        array( $this, 'loteria_metabox_html'),
        'loteria',
      );
  }

  public function loteria_metabox_html($post) {
    $getMeta = get_post_meta( $post->ID, '_loteria_resultados', true );
    ?>
      <h1>Veja os dados do concurso utilizando o shortcode [loterias loteria="megasena" concurso="122"]</h1>
    <?php
  }

  public function loteria_shortcode( $atts ) {

    if (isset($atts['concurso']) && is_numeric($atts['concurso'])) {
      $concurso = $atts['concurso'];

      $concursoData = static::loteria_get_resultados_by_concurso($concurso);

      if (empty( $concursoData )) {
        $url = "https://loteriascaixa-api.herokuapp.com/api/{$atts['loteria']}/{$concurso}";
        $response = vip_safe_wp_remote_get( $url );
        $data = json_decode( $response['body'] );

        static::loteria_save_post( $data );

        $concursoData = $response['body'];
      }
    } else {
      $concurso = 'latest';
      $url = "https://loteriascaixa-api.herokuapp.com/api/{$atts['loteria']}/{$concurso}";
      $response = vip_safe_wp_remote_get( $url );
      $data = json_decode( $response['body'] );

      $consultaConcurso = static::loteria_get_resultados_by_concurso($data->concurso);

      if (empty($consultaConcurso)) {
        static::loteria_save_post( $data );
      }

      $concursoData = $response['body'];
    }

    $template_args = array(
      'concursoData' => json_decode($concursoData, true),
    );

    return $this->loteria_load_template('loteria_concurso.php', $template_args);

  }

  private function loteria_load_template($template_name, $args = array()) {
      ob_start();
      extract($args);
      $concursoColor = $this->loteria_select_color( $concursoData['loteria'] );
      include(plugin_dir_path(__FILE__) . 'templates/' . $template_name);
      return ob_get_clean();
  }

  public static function loteria_get_resultados_by_concurso($concurso) {
      $args = array(
        'post_type' => 'loteria',
        'meta_query' => array(
            array(
                'key' => '_loteria_concurso',
                'value' => $concurso,
                'compare' => '=',
            )
        )
      );
      $query = new WP_Query($args);

      if ($query->have_posts()) {
          $query->the_post();
          $post_id = get_the_ID();
          $resultados = get_post_meta($post_id, '_loteria_resultados', true);
          return $resultados;
      }

      return false;
  }


  public static function loteria_save_post( $postData ) {

    $post['post_title']  = wp_strip_all_tags( "{$postData->loteria} - {$postData->concurso}" );
    $post['post_status'] = "publish";
    $post['post_type'] = 'loteria';
    $post['tags_input'] = array($postData->loteria);

    $postBody = wp_json_encode( $postData );

    $postId = wp_insert_post( $post );

    if ( !is_wp_error( $postId ) ) {
      add_post_meta( $postId, '_loteria_resultados', $postBody, true );
      add_post_meta( $postId, '_loteria_concurso', $postData->concurso, true );
    }
  }

  private function loteria_select_color( $loteria ) {
    switch ($loteria) {
      case 'maismilionaria':
        $loteriaColor = "#123497";
        break;
      case 'lotofacil':
        $loteriaColor = "#921688";
        break;
      case 'quina':
        $loteriaColor = "#251383";
        break;
      case 'lotomania':
        $loteriaColor = "#f58124";
        break;
      case 'timemania':
        $loteriaColor = "#3daf3e";
        break;
      case 'duplasena':
        $loteriaColor = "#a41727";
        break;
      case 'federal':
        $loteriaColor = "#123497";
        break;
      case 'diadesorte':
        $loteriaColor = "#ca8536";
        break;
      case 'supersete':
        $loteriaColor = "#a9cf50";
        break;

      default:
        $loteriaColor = "#288c5f";
        break;
    }

    return $loteriaColor;
  }



}

Loteria::getInstance();
