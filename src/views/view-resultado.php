<?php
if (!defined('ABSPATH')) {
    exit();
}
function lotcaixa_view_loteria_resultado($args) {
    // Assets do plugins        
    wp_enqueue_style('lotcaixa-root-style');  
    wp_enqueue_style('fonte-inter'); 
    wp_enqueue_style('lotcaixa-style');    

    // Inicialize a variável $html
    $html = '<div id="loteria" class="loterias-wrap loteria-' . esc_attr($args['loteria']) . '">';
    $html .= '<div id="concurso" class="col-concurso">';
    $html .= '<div class="cabecalho">' . esc_html($args['titulo']) . '</div>';
    $html .= '<div class="dezenas"><ul class="dezenas-itens">';
    
    foreach ($args['dezenas'] as $dezena) {
        $html .= '<li>' . esc_html($dezena) . '</li>';
    }

    $html .= '</ul></div>';
    $html .= '<div class="premiacao">';
    $html .= '<p>PRÊMIO</p>';
    $html .= '<p>R$ ' . esc_html($args['valor_estimado']) . '</p>';
    $html .= '</div>';
    $html .= '<table id="table-premiacoes">';
    $html .= '<tr><th>Faixas</th><th>Ganhadores</th><th>Prêmio</th></tr>';

    foreach ($args['premiacoes'] as $premiacao) {
        $html .= '<tr>';
        $html .= '<td>' . esc_html($premiacao['descricao']) . '</td>';
        $html .= '<td>' . esc_html($premiacao['ganhadores']) . '</td>';
        $html .= '<td>R$ ' . esc_html($premiacao['valorPremio']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $html .= '</div></div></div>';

    // Retorna a variável $html
    return $html;
}
