<?php
/*
Plugin Name: Loterias CAIXA
Description: Plugin para exibir resultados das loterias da CAIXA.
Plugin URI: https://github.com/adomoraes/teste-fullstack
Version: 1.0
Author: Eduardo Moraes
Author URI: https://github.com/adomoraes/
*/

define('LOTERIASCAIXA_PATH', plugin_dir_path(__FILE__));
include_once(LOTERIASCAIXA_PATH . 'includes/lc-functions.php');

add_action('init', 'lc_postTypeRegister');

function lc_shortcode($atts) {

    $atts = shortcode_atts(array(
        'loteria' => '0',
        'concurso' => '0',
    ), $atts);

    if ($atts['loteria'] !== '0') {
        $loteria = $atts['loteria'];
        if ($atts['concurso'] === 'ultimo' || $atts['concurso'] == NULL) {
            $concurso = 'latest';
        } else {
            $concurso = $atts['concurso'] !== '0' ? $atts['concurso'] : 'latest';
        }
    }

    global $wpdb;

    if (is_numeric($concurso)) {
        $checkPostId = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'loterias' AND post_title LIKE %s",
            'Concurso ' . $concurso . '%'
        ));

        if ($checkPostId) {
            $post_content = get_post_field('post_content', $checkPostId);
            $dados = json_decode($post_content, true);
            $weekDay = lc_formatDateName($dados['data']);

            $html = '<div class="loterias-caixa">';
            $html .= '<div class="card-header color-theme ' . $atts['loteria'] . '">Concurso ' . $dados['concurso'] . ' • ' . $weekDay . ' ' . $dados['data'] . '</div>';
            $html .= '<div class="card-dezenas">';
            $html .= '<ul>';
            foreach ($dados['dezenas'] as $dezena) {
                $html .= '<li class="color-theme ' . $atts['loteria'] . '">' . $dezena . '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '<div class="card-premio">
                    <p>Prêmio</p>
                    R$ ' . number_format($dados['valorArrecadado'], 2, ',', '.') .
                '</div>';
            $html .= '<table>
                    <thead>
                        <tr>
                            <th class="color-theme ' . $atts['loteria'] . '">Faixas</th>
                            <th class="color-theme ' . $atts['loteria'] . '">Ganhadores</th>
                            <th class="color-theme ' . $atts['loteria'] . '">Prêmio</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($dados['premiacoes'] as $premiacao) {
                $faixaName = lc_formatFaixasName($premiacao['faixa'], $premiacao['descricao']);

                $html .= '<tr>';
                $html .= '<td>' . $faixaName . '</td>';
                $html .= '<td>' . $premiacao['ganhadores'] . '</td>';
                $html .= '<td> R$ ' . number_format($premiacao['valorPremio'], 2, ',', '.') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $html .= '</div>';
        }
    } else {
        $url = 'https://loteriascaixa-api.herokuapp.com/api/' . $loteria . '/' . $concurso;

        $args = array(
            'method' => 'GET',
            'timeout' => 30,
        );
        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Ocorreu um erro ao tentar acessar a API: $error_message";
        } else {

            $dados = json_decode(wp_remote_retrieve_body($response));
            $postId = lc_createPost($dados);
            $weekDay = lc_formatDateName($dados->data);

            if ($atts['concurso'] !== '0') {

                $html = '<div class="loterias-caixa">';
                $html .= '<div class="card-header color-theme ' . $atts['loteria'] . '">Concurso ' . $dados->concurso . ' • ' . $weekDay . ' ' . $dados->data . '</div>';
                $html .= '<div class="card-dezenas">';
                $html .= '<ul>';
                foreach ($dados->dezenas as $dezena) {
                    $html .= '<li class="color-theme ' . $atts['loteria'] . '">' . $dezena . '</li>';
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
                    <th class="color-theme ' . $atts['loteria'] . '">Faixas</th>
                    <th class="color-theme ' . $atts['loteria'] . '">Ganhadores</th>
                    <th class="color-theme ' . $atts['loteria'] . '">Prêmio</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($dados->premiacoes as $premiacao) {
                    $faixaName = lc_formatFaixasName($premiacao->faixa, $premiacao->descricao);

                    $html .= '<tr>';
                    $html .= '<td>' . $faixaName . '</td>';
                    $html .= '<td>' . $premiacao->ganhadores . '</td>';
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
        }
    }

    return $html;
}
add_shortcode('loterias_caixa', 'lc_shortcode');

function lc_customStyles() {
    wp_enqueue_style('styles', plugins_url('css/styles.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'lc_customStyles');

//echo do_shortcode('[loterias_caixa loteria="megasena" concurso=""]');
