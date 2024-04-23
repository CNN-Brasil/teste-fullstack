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
        foreach ($this->shortcode_prefixes as $prefix) {
            add_shortcode($prefix, [$this, 'shortcode_handler']);
        }

        add_action('init', [$this, 'register_loterias_post_type']);
    }

    public function shortcode_handler($atts, $content = null, $tag = '') {

        if (empty($atts[0])) {
            return "<p>Erro: O shortcode requer um argumento identificador do sorteio.</p>";
        }
    
        $concurso_name = esc_html($tag);

        if(esc_html($atts[0]) == "ultimo"){
            $concurso_id = $this->get_latest_concurso_id($concurso_name);
        } else {
            $concurso_id = esc_html($atts[0]);
        }

        $post_name = "{$concurso_name}-{$concurso_id}";
    
        $post_data = $this->find_loterias_post($concurso_name, $concurso_id);
    
        if ($post_data) {

            $post_content = $post_data;
            $template = $this->fill_template($post_content);
            return $template;
        } else {

            $api_data = $this->fetch_data_from_api($concurso_name, $concurso_id);
    
            if ($api_data) {
                $new_post_id = $this->create_loterias_post($post_name, $api_data);
                $post_content = get_post_field('post_content', $new_post_id);

                $template = $this->fill_template($post_content);
                return $template;

            } else {
                return "<p>Erro ao obter dados para a loteria {$post_name}.</p>";
            }
        }
    }

    public function real_formatter($valor) {
        $valor_formatado = number_format($valor, 2, ',', '.');
        return "R$ " . $valor_formatado;
    }

    public function get_day_of_week($date_str) {
        $date_obj = date_create_from_format("d/m/Y", $date_str);
        
        if (!$date_obj) {
            return "Data inválida";
        }
    
        $day_of_week_num = date_format($date_obj, "w");
    
        $days_of_week = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
    
        return $days_of_week[$day_of_week_num];
    }

    public function fill_template($json_content){

        $template = $this->load_template("loteria-template.html");

        $json_decoded = json_decode($json_content, true);

        $concurso_name = ucfirst($json_decoded["loteria"]);
        $concurso_type = $json_decoded["loteria"];
        $concurso_date = $json_decoded["data"];
        $concurso_date_week = $this->get_day_of_week($concurso_date);
        $concurso_id = $json_decoded["concurso"];
        $concurso_total_value = $this->real_formatter($json_decoded["valorArrecadado"]);

        $concurso_premiacoes_1_ganhadores = $json_decoded["premiacoes"][0]["ganhadores"];
        $concurso_premiacoes_1_premio = $this->real_formatter($json_decoded["premiacoes"][0]["valorPremio"]);

        $concurso_premiacoes_2_ganhadores = $json_decoded["premiacoes"][1]["ganhadores"];
        $concurso_premiacoes_2_premio = $this->real_formatter($json_decoded["premiacoes"][1]["valorPremio"]);

        $concurso_premiacoes_3_ganhadores = $json_decoded["premiacoes"][2]["ganhadores"];
        $concurso_premiacoes_3_premio = $this->real_formatter($json_decoded["premiacoes"][2]["valorPremio"]);

        $dezenas = $json_decoded["dezenas"];
        $dezenas_template = '';
        foreach ($dezenas as $dezena) {
            $dezenas_template .= "<span>{$dezena}</span>";
        }
    
        $output = str_replace(
            ["{{concurso_type}}", "{{concurso_name}}", "{{concurso_date}}", "{{concurso_date_week}}", "{{concurso_id}}", "{{dezenas_template}}","{{concurso_total_value}}","{{concurso_premiacoes_1_ganhadores}}","{{concurso_premiacoes_1_premio}}","{{concurso_premiacoes_2_ganhadores}}","{{concurso_premiacoes_2_premio}}","{{concurso_premiacoes_3_ganhadores}}","{{concurso_premiacoes_3_premio}}"],
            [$concurso_type, $concurso_name, $concurso_date, $concurso_date_week, $concurso_id, $dezenas_template, $concurso_total_value, $concurso_premiacoes_1_ganhadores, $concurso_premiacoes_1_premio, $concurso_premiacoes_2_ganhadores, $concurso_premiacoes_2_premio, $concurso_premiacoes_3_ganhadores, $concurso_premiacoes_3_premio],
            $template
        );

        return $output;
    }
    
    public function find_loterias_post($concurso_name, $concurso_id) {
        $post_name = "{$concurso_name}-{$concurso_id}";
    
        $query = new WP_Query(array(
            'post_type' => 'loterias',
            'name' => $post_name,
            'post_status' => 'publish',
            'posts_per_page' => 1
        ));
    
        if ($query->have_posts() && isset($query->posts[0])) {
            $post = $query->posts[0];
            return isset($post->post_content) ? $post->post_content : null;
        } else {
            return null; 
        }
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

    public function fetch_data_from_api($concurso_name, $concurso_id) {
        $api_url = "https://loteriascaixa-api.herokuapp.com/api/$concurso_name/$concurso_id";
        $response = wp_remote_get($api_url);
    
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return null;
        }
    
        $body = wp_remote_retrieve_body($response);
    
        return json_decode($body, true);
    }
    
    public function get_latest_concurso_id($concurso_name) {
        $api_url = "https://loteriascaixa-api.herokuapp.com/api/$concurso_name/latest";
        $response = wp_remote_get($api_url);
    
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return null;
        }
    
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
    
        if (isset($response_body['concurso'])) {
            return $response_body['concurso'];
        } else {
            return null;
        }
    }

    public function create_loterias_post($post_name, $api_data) {
    
        $new_post_id = wp_insert_post(array(
            'post_type' => 'loterias',
            'post_title' => $post_name,
            'post_content' => json_encode($api_data),
            'post_status' => 'publish',
            'post_name' => $post_name,
        ));
    
        return $new_post_id;
    }
    
}
