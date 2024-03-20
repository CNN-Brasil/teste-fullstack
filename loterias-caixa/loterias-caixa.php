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

        if ($atts['loteria'] !== '0') {

            $html = '<div class="loterias-caixa">';
            $html .= '<div class="card-header">Concurso ' . $dados->concurso . ' • ' . $dados->data . '</div>';
            $html .= '<div class="card-dezenas">';
            $html .= '<ul>';
            foreach ($dados->dezenas as $dezena) {
                $html .= "<li>$dezena</li>";
            }
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '<div class="card-premio">
                <p>Prêmio</p>
                R$ ' . number_format($dados->valorArrecadado, 2, ',', '.') . 
            '</div>';
            $html .= '<table>
                <thead>
                    <tr>
                        <th>Faixas</th>
                        <th>Ganhadores</th>
                        <th>Prêmio</th>
                    </tr>
                </thead>
                <tbody>';
                    foreach ($dados->premiacoes as $premiacao) {
                        $html .= '<tr>';
                        $html .= '<td>' . $premiacao->descricao . '</td>';
                        $html .= '<td>'. $premiacao->ganhadores . '</td>';
                        $html .= '<td> R$ ' . number_format($premiacao->valorPremio, 2, ',', '.') . '</td>';
                        $html .= '</tr>';
                    }
            $html .= '</tbody></table>';
            $html .= '</div>';
        } else {
            $html = '<div class="loterias-caixa">';
            $html .= '<p>Por favor, especifique o número do concurso usando o parâmetro "concurso".</p>';
            $html .= '</div>';
        }
 
    return $html;
}
add_shortcode('loterias_caixa', 'loterias_caixa_shortcode');

function loterias_caixa_custom_styles() {
    wp_enqueue_style('styles', plugins_url('css/styles.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'loterias_caixa_custom_styles');
