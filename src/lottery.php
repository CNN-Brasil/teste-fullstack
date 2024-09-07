<?php
/*
Plugin Name: Lottery
Description: Show the result for the Loterry with shortcode.
Version: 1.0
Author: Anderson Martins
*/

// Avoid direct access to the files
if (! defined('ABSPATH')) {
  exit;
}

// Load classes
require_once plugin_dir_path(__FILE__) . 'class/class-loterry-api.php';
require_once plugin_dir_path(__FILE__) . 'class/class-loterry-shortcode.php';

class LotteryPlugin
{

  public function __construct()
  {
    add_action('init', [$this, 'register_lottery_post_type']);
    add_action('init', ['LotteryShortcode', 'register_shortcode']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
    add_action('admin_menu', [$this, 'register_info_page']);
  }

  // Função que adiciona a página de informações ao menu do WordPress
  public function register_info_page()
  {
    add_menu_page(
      'Informações loterias',
      'Loterias Info',
      'manage_options',
      'loterias-caixa-info',
      [$this, 'info_page_content'],
      'dashicons-admin-site-alt3',
      6
    );
  }

  // Função que exibe o conteúdo da página de informações de como usar o plugin.
  public function info_page_content()
  {
?>
    <div class="wrap">
      <h1>Como usar o plugin de Loterias</h1>
      <p>No Editor Clássico (Classic Editor): No campo de edição de conteúdo, insira o shortcode diretamente, como no exemplo:</p>
      <code>[loterias loteria="megasena"]</code>
      <p>Para apresentação do último concurso</p>
      <hr />

      <p>Para um concurso específico, como no exemplo abaixo:</p>
      <code>[loterias loteria="quina" concurso="num_concurso"]</code>

      <br /><br />
      <h1> Usar o Shortcode no Arquivo PHP</h1>
      <code>&lt;?php echo do_shortcode('[loterias loteria="quina" concurso="latest"]'); ?&gt;</code>
      <hr />

      <h1>Parâmetros do Shortcode</h1>
      <ul>
        <li><strong>loteria</strong>: Especifica o nome da loteria. Exemplo: "megasena", "quina", etc.</li>
        <li><strong>concurso</strong>: Pode ser o número do concurso (como 1234) ou "ultimo" para buscar o concurso mais recente. Se este parâmetro não for definido, o padrão será "ultimo".</li>
      </ul>
      <p>Isso permite flexibilidade para exibir qualquer resultado de loteria, de concursos específicos ou do mais recente, em qualquer página, post ou até no tema WordPress.</p>
    </div>
<?php
  }

  // ADD CSS 
  public function enqueue_styles()
  {
    wp_enqueue_style(
      'loterias-caixa-style', // Handle do CSS
      plugin_dir_url(__FILE__) . 'assets/css/lottery.css', // Caminho para o arquivo CSS
      array(), // Dependências (se houver)
      '1.0.0', // Versão do arquivo
      'all' // Tipo de mídia
    );
  }

  // Register the Custom Post Type 'Lottery'
  public function register_lottery_post_type()
  {
    $labels = array(
      'name' => 'Loterias',
      'singular_name' => 'Loteria',
      'menu_name' => 'Resultados de Loterias',
      'all_items' => 'Todos os Resultados',
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'editor', 'custom-fields'),
      'show_in_menu' => true,
      'menu_position'=> 4
    );
    register_post_type('loterias', $args);
  }
}

// Start plugin
new LotteryPlugin();
