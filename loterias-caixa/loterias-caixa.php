<?php
/*
Plugin Name: Loterias CAIXA
Description: Plugin para exibir resultados das loterias da CAIXA.
Plugin URI: https://github.com/adomoraes/teste-fullstack
Version: 1.0
Author: Eduardo Moraes
Author URI: https://github.com/adomoraes/
*/

function loterias_caixa_shortcode($atts) {

    $atts = shortcode_atts(array(
        'loteria' => '0',
        'concurso' => '0',
    ), $atts);

    $loteria = $atts['loteria'];
    $concurso = $atts['concurso'];

    $url = 'https://loteriascaixa-api.herokuapp.com/api/'. $loteria .'/' . $concurso;

    $args = array(
        'method' => 'GET',
    );

    $response = wp_remote_get($url, $args);

    //DEBUG
    // echo '<pre>';
    // var_dump(wp_remote_retrieve_body($response));
    // echo '</pre>';

    $dados = json_decode(wp_remote_retrieve_body($response));
    
    //DEBUG
    //var_dump($dados);

        //Valida loteria
        if ($atts['loteria'] !== '0') {

            $html = '<div>';
            $html .= '<h2>Resultado do Concurso ' . $dados->concurso . ' da ' . $dados->loteria . '</h2>';
            $html .= '<p>Data: ' . $dados->data . '</p>';
            $html .= '<p>Local: ' . $dados->local . '</p>';
            $html .= '<p>Dezenas Sorteadas: ' . implode(', ', $dados->dezenasOrdemSorteio) . '</p>';
            $html .= '<p>Premiações:</p>';
            $html .= '<ul><li>PREMIAÇÕES</li></ul>';
            $html .= '<p>Valor Arrecadado: R$ ' . number_format($dados->valorArrecadado, 2, ',', '.') . '</p>';
            $html .= '<p>Acumulou: ' . ($dados->acumulou ? 'Sim' : 'Não') . '</p>';
            $html .= '<p>Próximo Concurso: ' . $dados->proximoConcurso . ' - Data: ' . $dados->dataProximoConcurso . '</p>';
            $html .= '<p>Valor Estimado do Próximo Concurso: R$ ' . number_format($dados->valorEstimadoProximoConcurso, 2, ',', '.') . '</p>';
            $html .= '</div>';
        } else {
            $html = '<div class="loterias-caixa">';
            $html .= '<p>Por favor, especifique o número do concurso usando o parâmetro "concurso".</p>';
            $html .= '</div>';
        }
    return $html;
}
add_shortcode('loterias_caixa', 'loterias_caixa_shortcode');
