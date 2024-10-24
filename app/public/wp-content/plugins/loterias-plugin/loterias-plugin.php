<?php

/**
 * Plugin Name: Loterias Plugin
 * Description: Plugin para exibir resultados das Loterias Caixa.
 * Version: 1.0
 * Author: Disney Andrade
 * Text Domain: loterias-plugin
 */


// Hook para inicializar o custom post type
function loterias_register_post_type()
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

    register_post_type('loterias', $args);
}
add_action('init', 'loterias_register_post_type');

// Função para registrar o shortcode
function loterias_shortcode($atts)
{
    // Atributos padrão do shortcode
    $atts = shortcode_atts(
        array(
            'loteria' => 'megasena', // Loteria padrão
            'concurso' => 'ultimo',  // Concurso padrão
        ),
        $atts
    );

    // Obter os valores do shortcode Obs.:sanitize_text_field para (limpar dados)
    $loteria = sanitize_text_field($atts['loteria']);
    $concurso = sanitize_text_field($atts['concurso']);

    // Buscar os resultados da API
    $resultados = loterias_get_resultados($loteria, $concurso);

    // Verificar se houve erro
    if (is_string($resultados)) {
        return $resultados;  // Retornar mensagem de erro se houver
    }

    // Verificar se os dados esperados estão disponíveis
    if (!isset($resultados['dezenas']) || !is_array($resultados['dezenas'])) {
        return "Nenhum resultado disponível no momento.";
    }

    // Salvar os resultados no post type "Loterias"
    loterias_save_resultados($loteria, $concurso, $resultados);

    //para troca de cor conforme a loteria
    $bg = '';
    $fontColor = '';
    switch ($resultados['loteria']) {
        case 'megasena':
            # code...
            $bg = "style='background: #298C5F;'";
            $fontColor = "style='color: #298C5F;'";

            break;
        case 'quina':
            # code...
            $bg = "style='background: #261383;'";
            $fontColor = "style='color: #261383;'";
            break;
        case 'lotofacil':
            # code...
            $bg = "style='background: #921788;'";
            $fontColor = "style='color: #921788;'";
            break;
        case 'lotomania':
            # code...
            $bg = "style='background: #F58123;'";
            $fontColor = "style='color: #F58123;'";
            break;
        case 'timemania':
            # code...
            $bg = "style='background: #3DAF3E;'";
            $fontColor = "style='color: #3DAF3E;'";
            break;
        case 'duplasena':
            # code...
            $bg = "style='background: #A41628;'";
            $fontColor = "style='color: #A41628;'";
            break;
        case 'federal':
            # code...
            $bg = "style='background: #133497;'";
            $fontColor = "style='color: #133497;'";
            break;
        case 'diadesorte':
            # code...
            $bg = "style='background: #CA8536;'";
            $fontColor = "style='color: #CA8536;'";
            break;
        case 'supersete':
            # code...
            $bg = "style='background: #A9CF50;'";
            $fontColor = "style='color: #A9CF50;'";
            break;
        default:
            $bg = "style='background: #298C5F;'";
            $fontColor = "style='color: #298C5F;'";
            break;
    }


    // Preparar a saída no frontend com o layout customizado
    $saida = "<div class='loteria-container'>";
    $saida .= "<div class='titulo' $bg >";
    $saida .= "<div class='text'>";
    $saida .= "<p>Concurso: {$resultados['concurso']} ♣ ".getDiaSemana($resultados['data'])." {$resultados['data']}</p>";
    $saida .= "</div>"; //fim do text
    $saida .= "</div>"; //fim faixa_topo
    $saida .= "<div class='dezenas divisao'>"; //dezenas
    foreach ($resultados['dezenas'] as $key => $value) {
        # code...
        $saida .= "<p class='numeros-sorteados' $bg> "  . $value . "</p>";
    }
    $saida .= "</div>"; //dezenas
    $saida .= "<div class='premio divisao'>"; //premio
    $valorFormatado = 'R$ ' . number_format($resultados['valorArrecadado'], 2, ',', '.');
    $saida .= "<p class='valor-premio'> PRÊMIO <br />" . $valorFormatado . "</p>";
    $saida .= "</div>"; //premio
    $saida .= "<div class='faixas'>"; //faixas
    $saida .= "<div class='titulos divisao hgt'>"; //faixas
    $saida .= "<span class='faixa' $fontColor > Faixas </span>";
    $saida .= "<span class='faixa' $fontColor > Ganhadores </span>";
    $saida .= "<span class='faixa' $fontColor > Prêmio </span>";
    $saida .= "</div>"; //titulos
    $saida .= "<div class='colunas '>"; //colunas

    $saida .= "<div class='col '>"; //faixa
    foreach ($resultados['premiacoes'] as $arrValue) {
        $saida .= "<div class='divisao hgt'>"; //faixa
        if ($resultados['loteria'] === 'megasena') {
            switch ($arrValue['faixa']) {
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
            $saida .= $arrValue['faixa'];
        }

        $saida .= "</div>"; //faixa
    }
    $saida .= "</div>"; //faixa

    $saida .= "<div class='col'>";
    foreach ($resultados['premiacoes'] as $arrValue) {
        $saida .= "<div class='divisao hgt'>";
        $saida .= $arrValue['ganhadores'];
        $saida .= "</div>";
    }
    $saida .= "</div>"; //ganhadores    

    $saida .= "<div class='col'>"; //valorPremio
    foreach ($resultados['premiacoes'] as $arrValue) {
        $saida .= "<div class='divisao hgt'>";
        $saida .= "R$ " . number_format($arrValue['valorPremio'], 2, ',', '.');
        $saida .= "</div>";
    }
    $saida .= "</div>"; //valorPremio

    $saida .= "</div>"; //colunas

    // Fechar o container
    $saida .= "</div>";

    // Retornar a saída final
    return $saida;
}

add_shortcode('loteria', 'loterias_shortcode');


// Função para buscar resultados da API
function loterias_get_resultados($loteria, $concurso)
{
    //cache
    //cahe key
    $cache_key = "loteria_{$loteria}_{$concurso}";
    //verifica se o resultado já está no cache
    $cached_result = get_transient($cache_key);
    //retorna o cached_result se o resultado já estiver  nele
    if ($cached_result) {
        return $cached_result;
    }
    //fim do cache

    // URL base da API
    $api_url = "https://loteriascaixa-api.herokuapp.com/api/";

    // Se o concurso for 'ultimo', monta a URL para o último concurso
    if ($concurso === 'ultimo') {
        $api_url .= "{$loteria}/latest";
    } else {
        // Caso contrário, usa o número do concurso fornecido
        $api_url .= "{$loteria}/{$concurso}";
    }

    // Usar wp_remote_get() para fazer a chamada à API
    $response = wp_remote_get($api_url);

    // Verificar se houve erro na requisição
    if (is_wp_error($response)) {
        return "Erro ao obter os resultados da API.";
    }

    // Obter o corpo da resposta
    $body = wp_remote_retrieve_body($response);

    // Decodificar o JSON da API
    $data = json_decode($body, true);

    //armazenando no cache
    set_transient($cache_key, $data, 3600); // 3600 tempo de 1h

    // Retornar os dados decodificados
    return $data;
}

//salva o concurso no post type loterias
function loterias_save_resultados($loteria, $concurso, $resultados)
{
    // Verificando se os dados esperados estão disponíveis e têm o formato correto
    if (!isset($resultados['concurso']) || !is_array($resultados['concurso']) || !isset($resultados['concurso']['numero'])) {
        return; // Se os dados estiverem ausentes ou malformados, sair da função
    }

    //verificando se o concurso já está salvo no post type
    $existing_post = get_posts(array(
        'post_type' => 'loterias',
        'meta_query' => array(
            array(
                'key' => 'concurso_numero',
                'value' => $resultados['concurso']['numero'],
                'compare' => '='
            )
        )
    ));

    if (!empty($existing_post)) {
        return; // O concurso já está salvo, então não precisa fazer nada
    }

    // Se não estiver salvo, cria-se um novo post
    $post_id = wp_insert_post(array(
        'post_title' => "{$loteria} Concurso {$concurso}",
        'post_content' => "Resultado do concurso {$concurso} da loteria {$loteria}.",
        'post_status' => 'publish',
        'post_type' => 'loterias',
    ));

    // Salvar os resultados como meta
    if ($post_id) {
        update_post_meta($post_id, 'concurso_numero', $resultados['concurso']['numero']);
        update_post_meta($post_id, 'data_concurso', $resultados['concurso']['data']);
        update_post_meta($post_id, 'numeros_sorteados', implode(", ", $resultados['dezenas']));
    }
}


// Função para enfileirar o estilo CSS
function loterias_enqueue_styles()
{
    // Registrar o arquivo de estilo
    wp_enqueue_style(
        'loterias-plugin-style', // Nome do estilo
        plugins_url('css/style.css', __FILE__) // Caminho para o arquivo de estilo
    );
}
add_action('wp_enqueue_scripts', 'loterias_enqueue_styles');

function getDiaSemana($data) {
    // Converte a string em date
    $dataObj = DateTime::createFromFormat('d/m/Y', $data);

    // Array com os dias da semana em português
    $diasSemana = [
        'Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira',
        'Quinta-Feira', 'Sexta-Feira', 'Sábado'
    ];

    // pega o índice do dia da semana (0 = domingo, 1 = segunda, e por aí vai)
    $diaSemanaIndex = $dataObj->format('w');

    // Retornar o nome do dia da semana em português
    return $diasSemana[$diaSemanaIndex];
}

// Exemplo de uso
// $data = '22/10/2024';
// $diaSemana = getDiaSemana($data);

// echo $diaSemana; // Retorna 'terça-feira'
