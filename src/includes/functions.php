<?php
if (!defined('ABSPATH')) {
    exit();
}
function lotcaixa_check_cpt_by_title($title) {    
    $args = array(
        'post_type' => 'loterias',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'post_title' => $title,
    );

    $query = new WP_Query($args);

    $cpt_ids = $query->posts;

    return !empty($cpt_ids) ? $cpt_ids[0] : false;
}

function lotcaixa_what_day_week($data) {
    $data_invertida = implode('-', array_reverse(explode('/', $data)));
    
    $timestamp = strtotime($data_invertida);
    
    $dia_da_semana_ingles = gmdate('l', $timestamp); 
    
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
    $query = new WP_Query($args);

    $cpt_ids = $query->posts;

    return !empty($cpt_ids) ? $cpt_ids[0] : false;    
}


