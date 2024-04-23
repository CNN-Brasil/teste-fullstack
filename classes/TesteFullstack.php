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


    
        $contest_name = esc_html($tag);

        if(empty($atts[0]) || esc_html($atts[0]) == "ultimo"){
            $contest_id = $this->get_latest_contest_id($contest_name);
        } else {
            $contest_id = esc_html($atts[0]);
        }

        $post_name = "{$contest_name}-{$contest_id}";
    
        $post_data = $this->find_loterias_post($contest_name, $contest_id);
    
        if ($post_data) {

            $post_content = $post_data;
            $template = $this->fill_template($post_content);
            return $template;
        } else {

            $api_data = $this->fetch_data_from_api($contest_name, $contest_id);
    
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

    public function fill_template($json_content) {
        $template = $this->load_template("loteria-template.html");
    
        $json_decoded = json_decode($json_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Conteúdo JSON inválido");
        }
    
        $contest_name = ucfirst($json_decoded["loteria"] ?? 'N/A');
        $contest_type = $json_decoded["loteria"] ?? 'N/A';
        $contest_date = $json_decoded["data"] ?? 'N/A';
        $contest_date_week = $this->get_day_of_week($contest_date);
        $contest_id = $json_decoded["concurso"] ?? 'N/A';
        $contest_total_value = $this->real_formatter($json_decoded["valorArrecadado"] ?? 0);
    
        $dozens = $json_decoded["dezenas"] ?? [];
        $dozens_template = '';
        foreach ($dozens as $dozen) {
            $dozens_template .= "<span>" . htmlspecialchars($dozen) . "</span>";
        }
    
        $premiacoes = [];
        if (isset($json_decoded["premiacoes"]) && is_array($json_decoded["premiacoes"])) {
            foreach ($json_decoded["premiacoes"] as $premiacao) {
                $ganhadores = $premiacao["ganhadores"] ?? 0;
                $premio = $this->real_formatter($premiacao["valorPremio"] ?? 0);
                $premiacoes[] = ["ganhadores" => $ganhadores, "premio" => $premio];
            }
        }
    
        $replacements = [
            '{{contest_type}}' => esc_html($contest_type),
            '{{contest_name}}' => esc_html($contest_name),
            '{{contest_date}}' => esc_html($contest_date),
            '{{contest_date_week}}' => esc_html($contest_date_week),
            '{{contest_id}}' => esc_html($contest_id),
            '{{dozens_template}}' => $dozens_template,
            '{{contest_total_value}}' => esc_html($contest_total_value),
        ];
    
        foreach ($premiacoes as $index => $premiacao) {
            $replacements["{{contest_" . ($index + 1) . "_winners}}"] = esc_html($premiacao["ganhadores"]);
            $replacements["{{contest_" . ($index + 1) . "_award}}"] = esc_html($premiacao["premio"]);
        }
    
        $output = str_replace(array_keys($replacements), array_values($replacements), $template);
    
        return $output;
    }
    
    
    public function find_loterias_post($contest_name, $contest_id) {
        $post_name = "{$contest_name}-{$contest_id}";
    
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

    public function fetch_data_from_api($contest_name, $contest_id) {
        $api_url = "https://loteriascaixa-api.herokuapp.com/api/$contest_name/$contest_id";
        $response = wp_remote_get($api_url);
    
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return null;
        }
    
        $body = wp_remote_retrieve_body($response);
    
        return json_decode($body, true);
    }
    
    public function get_latest_contest_id($contest_name) {
        $api_url = "https://loteriascaixa-api.herokuapp.com/api/$contest_name/latest";
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
