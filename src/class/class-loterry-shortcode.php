<?php

class LotteryShortcode
{

  // Register the shortcode 
  public static function register_shortcode()
  {
    add_shortcode('loterias', [__CLASS__, 'render_shortcode']);
  }

  // Functio to render the shortcode
  public static function render_shortcode($atts)
  {
    $atts = shortcode_atts(array(
      'loteria' => 'megasena',
      'concurso' => 'latest',
    ), $atts);

    $api = new LotteryAPI();
    $concurso = $atts['concurso'];
    $loteria = $atts['loteria'];

    if ($concurso !== 'latest') {
      $result = self::get_loteria_post($loteria, $concurso);
      if ($result) {
        return self::render_template($result);
      }
    }

    $data = $api->get_concurso($loteria, $concurso);
    if (!$data) {
      return '<p>Não foi possível obter os resultados no momento.</p>';
    }

    if ($concurso === 'latest' || !self::get_loteria_post($loteria, $data['numero'])) {
      self::save_loteria_post($loteria, $data);
    }

    return self::render_template($data);
  }

  // Function to search the post at Lottery Custom Post
  private static function get_loteria_post($loteria, $concurso)
  {
    $query = new WP_Query(array(
      'post_type' => 'loterias',
      'meta_query' => array(
        array(
          'key' => 'loteria',
          'value' => $loteria,
        ),
        array(
          'key' => 'concurso',
          'value' => $concurso,
        ),
      ),
    ));
    return $query->have_posts() ? $query->posts[0] : false;
  }

  // Function to save the post at post-type 'Lottery'
  private static function save_loteria_post($loteria, $data)
  {
    $post_id = wp_insert_post(array(
      'post_title' => $loteria . ' Concurso ' . $data['numero'],
      'post_type' => 'loterias',
      'post_status' => 'publish',
    ));
    update_post_meta($post_id, 'loteria', $loteria);
    update_post_meta($post_id, 'concurso', $data['numero']);
    update_post_meta($post_id, 'resultado', $data['resultado']);
  }

  // Function to render the template of Shortcode
  private static function render_template($data)
  {
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/loterry-tmpl.php';
    return ob_get_clean();
  }
}
