<?php

class Loteria_CPT
{

  public static function register_post_type()
  {
    $labels = [
      'name' => 'Loterias',
      'singular_name' => 'Loteria',
      'add_new_item' => 'Adicionar Novo Resultado',
      'edit_item' => 'Editar Resultado',
    ];

    $args = [
      'labels' => $labels,
      'public' => true,
      'has_archive' => true,
      'show_in_rest' => true,
      'supports' => ['title', 'editor', 'custom-fields'],
    ];

    register_post_type('loterias', $args);
  }

  public static function save_loteria_result($loteria, $concurso, $result)
  {
    $post_data = [
      'post_title'  => "{$loteria} - Concurso {$concurso}",
      'post_type'   => 'loterias',
      'post_status' => 'publish',
      'meta_input'  => [
        'concurso' => $concurso,
        'resultados' => $result
      ],
    ];

    wp_insert_post($post_data);
  }
}
