<?php

class Loteria_Shortcode
{
  public static function register_shortcode()
  {
    add_shortcode('loteria_result', [__CLASS__, 'display_loteria_result']);
  }

  /**
   * Exibe o resultado da loteria com base nos parâmetros fornecidos.
   *
   * @param array $atts Atributos do shortcode (loteria, concurso).
   *
   * @return string HTML do resultado da loteria.
   */
  public static function display_loteria_result($atts)
  {
    // Parâmetros do shortcode: 'loteria' (nome da loteria) e 'concurso' (número ou latest)
    $atts = shortcode_atts([
      'loteria' => 'megasena', // Loteria padrão caso nenhum seja passado
      'concurso' => 'latest',  // Padrão é buscar o último concurso
    ], $atts);

    $loteria = sanitize_text_field($atts['loteria']);
    $concurso = sanitize_text_field($atts['concurso']);

    // Verificar se o concurso já existe no CPT com a loteria correta
    $args = [
      'post_type' => 'loterias',
      'posts_per_page' => 1,
      'meta_query' => [
        [
          'key' => 'concurso',
          'value' => $concurso,
          'compare' => '='
        ],
        [
          'key' => 'loteria',
          'value' => $loteria,
          'compare' => '='
        ]
      ]
    ];

    $query = new WP_Query($args);


    // Se não encontrar o concurso no CPT, buscar na API.
    if (! $query->have_posts()) {
      $result = Loteria_API::get_loteria_result($loteria, $concurso);

      if ($result) {
        // Salvar o resultado no CPT.
        Loteria_CPT::save_loteria_result($loteria, $concurso, $result);
      } else {
        return 'Resultado não encontrado para a loteria ' . esc_html($loteria) . '.';
      }
    } else {
      // Se o concurso já existir no CPT, usar o resultado armazenado.
      $result = get_post_meta($query->posts[0]->ID, 'resultados', true);
    }

    // Renderizar template com o resultado.
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/template-loteria-result.php';
    return ob_get_clean();
  }
}
