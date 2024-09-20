<?php

use LoteriasCaixa\includes\Loteria_Front;

function exibir_resultados_loterias($atts)
{
    // Definindo os padrões
    $atts = shortcode_atts(
        array(
            'loteria' => 'megasena', // Nome padrão
            'concurso' => 'ultimo',   // Concurso padrão
        ),
        $atts
    );

    // Verificar parâmetros vazios e ajustar para valores padrão
    if (empty($atts['loteria'])) {
        $atts['loteria'] = 'megasena';
    }
    if (empty($atts['concurso'])) {
        $atts['concurso'] = 'ultimo';
    }

    // Se o concurso for 'ultimo', a API usa o valor 'latest'
    if ($atts['concurso'] === 'ultimo') {
        $atts['concurso'] = 'latest';
    }

    // Chama a função para obter resultados, passando os parâmetros do shortcode
    $resultados = obter_resultados($atts['loteria'], $atts['concurso']);

    if (is_wp_error($resultados) || empty($resultados)) {
        return "<div>Não foi encontrado o concurso {$atts['concurso']} na loteria {$atts['loteria']}.</div>";
    }

    // Retorna os resultados já formatados pela classe Loteria_Front
    $loteria_front = new Loteria_Front();
    return $loteria_front->formatar_resultados_html($resultados);
}

function obter_resultados($loteria, $concurso)
{
    $transient_key = 'resultados_' . sanitize_title($loteria) . '_' . sanitize_title($concurso);

    // Tenta obter os resultados do cache
    $resultados = get_transient($transient_key);
    if ($resultados !== false) {
        return $resultados; // Retorna do cache
    }

    // Se não estiver no cache, faz a chamada à API
    $url = "https://loteriascaixa-api.herokuapp.com/api/$loteria/$concurso";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return $response; // Retorna o erro
    }

    $body = wp_remote_retrieve_body($response);
    $resultados = json_decode($body, true);

    if (empty($resultados) || !isset($resultados['concurso'])) {
        return false; // Retorna false para indicar erro
    }

    // Armazena os resultados no cache por 1 hora
    set_transient($transient_key, $resultados, HOUR_IN_SECONDS);

    return $resultados;
}
