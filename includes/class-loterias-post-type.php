<?php
class Loterias_Post_Type
{

  public function __construct()
  {
    add_action('init', array($this, 'register_post_type'));
  }

  public function register_post_type()
  {

    $labels = array(
      'name'               => __('Loterias', 'loterias'),
      'singular_name'      => __('Loteria', 'loterias'),
      'menu_name'          => __('Loterias', 'loterias'),
      'parent_item_colon'  => __('Loteria pai:', 'loterias'),
      'all_items'          => __('Todas as loterias', 'loterias'),
      'add_new_item'       => __('Adicionar nova loteria', 'loterias'),
      'edit_item'          => __('Editar loteria', 'loterias'),
      'new_item'           => __('Nova loteria', 'loterias'),
      'view_item'          => __('Ver loteria', 'loterias'),
      'search_items'       => __('Procurar loterias', 'loterias'),
      'not_found'          => __('Nenhuma loteria encontrada', 'loterias'),
      'not_found_in_trash' => __('Nenhuma loteria na lixeira', 'loterias'),
      'menu_icon'          => 'dashicons-money',
    );

    $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'loterias'),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'supports'           => array('title', 'editor'),
    );

    register_post_type('loterias', $args);
  }
}
