<?php
if (!defined('ABSPATH')) {
    exit();
}
// Verifica se o CPT existe com o título fornecido
function lotcaixa_check_cpt_by_title($title) {    
    $args = array(
        'post_type' => 'loterias',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'post_title' => $title,
    );

    // Executa a consulta
    $query = new WP_Query($args);

    // Verifica se há posts correspondentes
    $cpt_ids = $query->posts;

    // Retorna o ID do primeiro post correspondente (se existir) ou falso
    return !empty($cpt_ids) ? $cpt_ids[0] : false;
}

// Obtem o dia da semana por meio de uma data DD/MM/YYYY
function lotcaixa_what_day_week($data) {
    // Inverte a ordem da data para o formato americano
    $data_invertida = implode('-', array_reverse(explode('/', $data)));
    
    // Obtém o timestamp da data
    $timestamp = strtotime($data_invertida);
    
    // Obtém o nome completo do dia da semana em inglês
    $dia_da_semana_ingles = gmdate('l', $timestamp); // Alterado para gmdate()
    
    // Traduz o nome do dia da semana para o português
    switch ($dia_da_semana_ingles) {
        case 'Monday':
            return 'Segunda-feira';
        case 'Tuesday':
            return 'Terça-feira';
        case 'Wednesday':
            return 'Quarta-feira';
        case 'Thursday':
            return 'Quinta-feira';
        case 'Friday':
            return 'Sexta-feira';
        case 'Saturday':
            return 'Sábado';
        case 'Sunday':
            return 'Domingo';
        default:
            return 'Dia desconhecido';
    }
}


// Verificar se existe um cpt loteria pelo numero do concurso
function lotcaixa_check_loteria_by_concurso($concurso){
    $args = array(
        'post_type' => 'loterias',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query'     => array(
            array(
                'key'     => 'concurso',
                'value'   => $concurso,
                'compare' => '=',
            ),
        ),        
    );
    // Executa a consulta
    $query = new WP_Query($args);

    // Verifica se há posts correspondentes
    $cpt_ids = $query->posts;

    // Retorna o ID do primeiro post correspondente (se existir) ou falso
    return !empty($cpt_ids) ? $cpt_ids[0] : false;    
}


