<?php

class TesteFullstack {
    private $shortcode_prefixes = [
        "maismilionaria",
        "megasena",
        "lotofacil",
        "quina",
        "lotomania",
        "timemania",
        "duplasena",
        "federal",
        "diadesorte",
        "supersete"
    ];

    public function __construct() {
        // Registrar todos os shortcodes usando um loop
        foreach ($this->shortcode_prefixes as $prefix) {
            add_shortcode($prefix, [$this, 'shortcode_handler']);
        }

        // Registra o Custom Post Type
        add_action('init', [$this, 'register_loterias_post_type']);
    }

    public function shortcode_handler($atts) {
        // Verificar se o argumento posicional está presente
        if (empty($atts[0])) {
            return "<p>Erro: O shortcode requer um argumento numérico.</p>";
        }

        // Pegar o número do shortcode
        $concurso = esc_html($atts[0]);

        // Carregar o template externo
        $template = $this->load_template("loteria-template.html");

        // Substituir o placeholder pela variável do shortcode
        $output = str_replace("{{concurso}}", $concurso, $template);

        return $output;
    }

    public function load_template($filename) {
        $filepath = plugin_dir_path(__FILE__) . "../templates/" . $filename;

        if (file_exists($filepath)) {
            return file_get_contents($filepath);
        } else {
            return "<p>Erro: template não encontrado.</p>";
        }
    }

    public function register_loterias_post_type() {
        $labels = array(
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
            'menu_name' => 'Loterias',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'loteria'),
        );

        register_post_type('loterias', $args);
    }
}
