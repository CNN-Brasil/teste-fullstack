<?php

// Registrando post type
function lc_postTypeRegister() {
    $labels = array(
        'name'               => _x( 'Loterias', 'post type general name', 'loterias' ),
        'singular_name'      => _x( 'Loteria', 'post type singular name', 'loterias' ),
        'menu_name'          => _x( 'Loterias', 'admin menu', 'loterias' ),
        'name_admin_bar'     => _x( 'Loteria', 'add new on admin bar', 'loterias' ),
        'add_new'            => _x( 'Adicionar Nova', 'loteria', 'loterias' ),
        'add_new_item'       => __( 'Adicionar Nova Loteria', 'loterias' ),
        'new_item'           => __( 'Nova Loteria', 'loterias' ),
        'edit_item'          => __( 'Editar Loteria', 'loterias' ),
        'view_item'          => __( 'Ver Loteria', 'loterias' ),
        'all_items'          => __( 'Todas as Loterias', 'loterias' ),
        'search_items'       => __( 'Buscar Loterias', 'loterias' ),
        'parent_item_colon'  => __( 'Loteria Pai:', 'loterias' ),
        'not_found'          => __( 'Nenhuma loteria encontrada.', 'loterias' ),
        'not_found_in_trash' => __( 'Nenhuma loteria encontrada na lixeira.', 'loterias' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'rewrite'            => array( 'slug' => 'loterias' ),
        'supports'           => array( 'title', 'editor' )
    );

    register_post_type( 'loterias', $args );
}
// Criando post
function lc_createPost($data) {
    global $wpdb;

    if (empty($data->loteria) || empty($data->concurso) || empty($data->data)) {
        return new WP_Error('dados_faltando', 'Dados incompletos para cadastrar a loteria.');
    }

    // REFACT
    $checkPostId = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'loterias' AND post_title LIKE %s",
        'Concurso ' . $data->concurso . '%'
    ));

    if ($checkPostId) {
        return $checkPostId;
    }

    $formatedDate = date('Y-m-d', strtotime(str_replace('/', '-', $data->data)));

    $postContent = json_encode($data);

    $postData = array(
        'post_title'   => 'Concurso ' . $data->concurso . ' - ' . $data->loteria,
        'post_content' => $postContent,
        'post_status'  => 'publish',
        'post_type'    => 'loterias',
        'post_date'    => $formatedDate,
    );

    $postId = wp_insert_post($postData);

    if (is_wp_error($postId)) {
        return $postId;
    }

    return $postId;
}
// Formata faixas de premiação
function lc_formatFaixasName($data, $dataReturn) {

    switch ($data) {
        case 1:
            return "Sena";
        case 2:
            return "Quina";
        case 3:
            return "Quadra";
        case 4:
            return "Terno";
        case 5:
            return "Sena";
        case 6:
            return "Quina";
        case 7:
            return "Quadra";
        case 8:
            return "Terno";
        default:
            return $dataReturn;
    }

    return "Faixa não encontrada";
}
// Cria e formata o nome do dia da semana
function lc_formatDateName($data) {

    $dataObj = DateTime::createFromFormat('d/m/Y', $data);
    $dayWeekNumber = $dataObj->format('w');
    
    $dayWeekName = array(
        'Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'
    );
    $dayWeekName = $dayWeekName[$dayWeekNumber];

    return $dayWeekName;
}
?>