<?php

/**
 * Plugin Name: Loterias Plugin
 * Description: Plugin para exibir resultados das Loterias Caixa.
 * Version: 1.0
 * Author: Disney Andrade
 * Text Domain: loterias-plugin
 */

class LoteriasPlugin
{
    private $post_type = 'loterias';

    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('wp_enqueue_scripts', [$this, 'loterias_enqueue_styles']);
        add_shortcode('loteria', [$this, 'loterias_shortcode']);
    }
    // Registrar o Custom Post Type
    public function register_post_type()
    {
        $labels = array(
            'name'               => _x('Loterias', 'post type general name', 'loterias-plugin'),
            'singular_name'      => _x('Loteria', 'post type singular name', 'loterias-plugin'),
            'menu_name'          => _x('Loterias', 'admin menu', 'loterias-plugin'),
            'name_admin_bar'     => _x('Loteria', 'add new on admin bar', 'loterias-plugin'),
            'add_new'            => _x('Adicionar Nova', 'loteria', 'loterias-plugin'),
            'add_new_item'       => __('Adicionar Nova Loteria', 'loterias-plugin'),
            'new_item'           => __('Nova Loteria', 'loterias-plugin'),
            'edit_item'          => __('Editar Loteria', 'loterias-plugin'),
            'view_item'          => __('Ver Loteria', 'loterias-plugin'),
            'all_items'          => __('Todas as Loterias', 'loterias-plugin'),
            'search_items'       => __('Procurar Loterias', 'loterias-plugin'),
            'not_found'          => __('Nenhuma Loteria encontrada.', 'loterias-plugin'),
            'not_found_in_trash' => __('Nenhuma Loteria encontrada na lixeira.', 'loterias-plugin'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'loteria'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'custom-fields'),
        );

        register_post_type($this->post_type, $args);
    }
    // Shortcode para exibir resultados
    public function loterias_shortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'loteria' => 'megasena',
                'concurso' => 'ultimo',
            ),
            $atts
        );

        $loteria = sanitize_text_field($atts['loteria']);
        $concurso = sanitize_text_field($atts['concurso']);

        $resultados = $this->get_resultados($loteria, $concurso);

        if (is_string($resultados)) {
            return $resultados;
        }

        if (!isset($resultados['dezenas']) || !is_array($resultados['dezenas'])) {
            return "Nenhum resultado disponível no momento.";
        }

        $this->save_resultados($loteria, $concurso, $resultados);

        return $this->generate_front($resultados);
    }

    // Função para obter resultados da API
    private function get_resultados($loteria, $concurso)
    {
        $cache_key = "loteria_{$loteria}_{$concurso}";
        $cached_result = get_transient($cache_key);

        if ($cached_result) {
            return $cached_result;
        }

        $api_url = "https://loteriascaixa-api.herokuapp.com/api/";
        $api_url .= ($concurso === 'ultimo') ? "{$loteria}/latest" : "{$loteria}/{$concurso}";

        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            return "Erro ao obter os resultados da API.";
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        set_transient($cache_key, $data, 3600);

        return $data;
    }

    // Função para salvar resultados no post type
    private function save_resultados($loteria, $concurso, $resultados)
    {
        if (!isset($resultados['concurso']['numero'])) {
            return;
        }

        $existing_post = get_posts(array(
            'post_type' => $this->post_type,
            'meta_query' => array(
                array(
                    'key' => 'concurso_numero',
                    'value' => $resultados['concurso']['numero'],
                    'compare' => '='
                )
            )
        ));

        if (!empty($existing_post)) {
            return;
        }

        $post_id = wp_insert_post(array(
            'post_title' => "{$loteria} Concurso {$concurso}",
            'post_content' => "Resultado do concurso {$concurso} da loteria {$loteria}.",
            'post_status' => 'publish',
            'post_type' => $this->post_type,
        ));

        if ($post_id) {
            update_post_meta($post_id, 'concurso_numero', $resultados['concurso']['numero']);
        }
    }
    // Função para gerar a saída no frontend
    private function generate_front($resultados)
    {
        $styles = $this->get_loteria_styles($resultados['loteria']);
        $saida = "<div class='loteria-container'>";
        $saida .= "<div class='titulo' {$styles['bg']}>";
        $saida .= "<div class='text'>";
        $saida .= "<p>Concurso: {$resultados['concurso']} ♣ " . $this->getDiaSemana($resultados['data']) . " {$resultados['data']}</p>";
        $saida .= "</div>";
        $saida .= "</div>";

        $saida .= "<div class='dezenas divisao'>";
        foreach ($resultados['dezenas'] as $dezena) {
            $saida .= "<p class='numeros-sorteados' {$styles['bg']}> {$dezena}</p>";
        }
        $saida .= "</div>";

        $valorFormatado = 'R$ ' . number_format($resultados['valorArrecadado'], 2, ',', '.');
        $saida .= "<div class='premio divisao'>";
        $saida .= "<p class='valor-premio'> PRÊMIO <br />" . $valorFormatado . "</p>";
        $saida .= "</div>";

        $saida .= $this->generate_faixas($resultados, $styles['fontColor']);
        
        $saida .= "</div>";

        return $saida;
    }

    // Função para obter as cores de cada loteria
    private function get_loteria_styles($loteria)
    {
        $styles = [
            'megasena'   => ['bg' => "style='background: #298C5F;'", 'fontColor' => "style='color: #298C5F;'"],
            'quina'      => ['bg' => "style='background: #261383;'", 'fontColor' => "style='color: #261383;'"],
            'lotofacil'  => ['bg' => "style='background: #921788;'", 'fontColor' => "style='color: #921788;'"],
            'lotomania'  => ['bg' => "style='background: #F58123;'", 'fontColor' => "style='color: #F58123;'"],
            'timemania'  => ['bg' => "style='background: #3DAF3E;'", 'fontColor' => "style='color: #3DAF3E;'"],
            'duplasena'  => ['bg' => "style='background: #A41628;'", 'fontColor' => "style='color: #A41628;'"],
            'federal'    => ['bg' => "style='background: #133497;'", 'fontColor' => "style='color: #133497;'"],
            'diadesorte' => ['bg' => "style='background: #CA8536;'", 'fontColor' => "style='color: #CA8536;'"],
            'supersete'  => ['bg' => "style='background: #A9CF50;'", 'fontColor' => "style='color: #A9CF50;'"],
            'default'    => ['bg' => "style='background: #298C5F;'", 'fontColor' => "style='color: #298C5F;'"],
        ];

        return $styles[$loteria] ?? $styles['default'];
    }

    // Gerar faixas de premiação
    private function generate_faixas($resultados, $fontColor)
    {
        $saida = "<div class='faixas'>";
            $saida .= "<div class='titulos divisao hgt'>";
                $saida .= "<span class='faixa' {$fontColor}>Faixas</span>";
                $saida .= "<span class='faixa' {$fontColor}>Ganhadores</span>";
                $saida .= "<span class='faixa' {$fontColor}>Prêmio</span></div>";
            $saida .= "</div>";
        $saida .= "<div class='colunas '>"; //colunas

        $saida .= "<div class='col '>";
            foreach ($resultados['premiacoes'] as $premiacao) {
                $saida .= "<div class='divisao hgt'>"; //faixa
                if ($resultados['loteria'] === 'megasena') {
                    switch ($premiacao['faixa']) {
                        case '1':
                            # code...
                            $saida .= 'Sena';
                            break;
                        case '2':
                            # code...
                            $saida .= 'Quina';
                            break;
                        case '3':
                            # code...
                            $saida .= 'Quadra';
                            break;
                    }
                } else {
                    $saida .= $premiacao['faixa'];
                }

                $saida .= "</div>"; //fim divisao
            }
        $saida .= "</div>"; //col
        
        $saida .= $this->generate_ganhadores($resultados);
        $saida .= $this->generate_premios($resultados);
        

        $saida .= "</div>"; //colunas
        
        return $saida;
    }
    private function generate_ganhadores($resultados){
        $saida = "<div class='col'>";
        foreach ($resultados['premiacoes'] as $ganhadores) {
            $saida .= "<div class='divisao hgt'>";
            $saida .= $ganhadores['ganhadores'];
            $saida .= "</div>";
        }
        $saida .= "</div>"; //fim col ]

        return $saida;
    }
    private function generate_premios($resultados){
        $saida = "<div class='col'>"; //valorPremio
        foreach ($resultados['premiacoes'] as $premio) {
            $saida .= "<div class='divisao hgt'>";
            $saida .= "R$ " . number_format($premio['valorPremio'], 2, ',', '.');
            $saida .= "</div>";
        }
        $saida .= "</div>"; //fim col

        return $saida;
    }
    // Função para enfileirar o estilo CSS
    public function loterias_enqueue_styles()
    {
        // Registrar o arquivo de estilo
        wp_enqueue_style(
            'loterias-plugin-style', // Nome do estilo
            plugins_url('css/style.css', __FILE__) // Caminho para o arquivo de estilo
        );
    }


    // Função para pegar o nome do dia da semana a partir da data
    private function getDiaSemana($data)
    {
        // Converte a string em date
        $dataObj = DateTime::createFromFormat('d/m/Y', $data);

        // Array com os dias da semana em português
        $diasSemana = [
            'Domingo',
            'Segunda-Feira',
            'Terça-Feira',
            'Quarta-Feira',
            'Quinta-Feira',
            'Sexta-Feira',
            'Sábado'
        ];

        // pega o índice do dia da semana (0 = domingo, 1 = segunda, e por aí vai)
        $diaSemanaIndex = $dataObj->format('w');

        // Retornar o nome do dia da semana em português
        return $diasSemana[$diaSemanaIndex];
    }
}

new LoteriasPlugin();
