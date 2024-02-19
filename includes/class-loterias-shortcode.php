<?php
class Loterias_Shortcode
{
  public function __construct()
  {
    add_shortcode('loterias', array($this, 'render_shortcode'));
  }

  public function render_shortcode($atts)
  {

    $atts = shortcode_atts(array(
      'loteria' => '',
      'concurso' => 'latest',
    ), $atts);

    if (empty($atts['loteria'])) {
      return __('Erro: O parâmetro "loteria" é obrigatório.', 'loterias');
    }

    $loteria = sanitize_text_field($atts['loteria']);
    $concurso = sanitize_text_field($atts['concurso']);


    $api = new Loterias_API();


    $results = $api->get_results($loteria, $concurso);

    if (false === $results) {
      return __('Erro: Não foi possível obter os resultados da loteria.', 'loterias');
    }


    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/shortcode-loterias.php';
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
  }
}
