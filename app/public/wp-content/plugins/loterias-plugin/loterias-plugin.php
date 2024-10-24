<?php
/**
 * Plugin Name: Loterias Plugin
 * Description: Plugin para exibir resultados das Loterias Caixa.
 * Version: 1.0
 * Author: Disney Andrade
 * Text Domain: loterias-plugin
 */


 // Hook para inicializar o custom post type
 function loterias_register_post_type() {
    $labels = array(
        'name'               => _x( 'Loterias', 'post type general name', 'loterias-plugin' ),
        'singular_name'      => _x( 'Loteria', 'post type singular name', 'loterias-plugin' ),
        'menu_name'          => _x( 'Loterias', 'admin menu', 'loterias-plugin' ),
        'name_admin_bar'     => _x( 'Loteria', 'add new on admin bar', 'loterias-plugin' ),
        'add_new'            => _x( 'Adicionar Nova', 'loteria', 'loterias-plugin' ),
        'add_new_item'       => __( 'Adicionar Nova Loteria', 'loterias-plugin' ),
        'new_item'           => __( 'Nova Loteria', 'loterias-plugin' ),
        'edit_item'          => __( 'Editar Loteria', 'loterias-plugin' ),
        'view_item'          => __( 'Ver Loteria', 'loterias-plugin' ),
        'all_items'          => __( 'Todas as Loterias', 'loterias-plugin' ),
        'search_items'       => __( 'Procurar Loterias', 'loterias-plugin' ),
        'not_found'          => __( 'Nenhuma Loteria encontrada.', 'loterias-plugin' ),
        'not_found_in_trash' => __( 'Nenhuma Loteria encontrada na lixeira.', 'loterias-plugin' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'loteria' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'custom-fields' ),
    );

    register_post_type( 'loterias', $args );
}
add_action( 'init', 'loterias_register_post_type' );


