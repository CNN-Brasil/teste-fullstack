<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasInsertPost - Responsável por adicionar um novo post.
 */
class LoteriasInsertPost {

    /**
     * Adiciona um novo post com base nos dados fornecidos.
     *
     * @param array $data Dados a serem inseridos no post.
     * @return int|WP_Error ID do post inserido ou WP_Error em caso de falha.
     */
    public function add($data) {

        // Argumentos para a criação do post
        $args = array(
            'post_title' => wp_strip_all_tags($data['loteria'].' concurso '.$data['concurso']. ' - '.$data['data']),
            'post_type' => 'loterias',
            'post_status' => 'publish',
            'post_content'  => json_encode($data, JSON_UNESCAPED_UNICODE),
            'meta_input' => array(
                'concurso' => $data['concurso']
            ),
            'tax_input' => array(
                'tipo_concurso' => array(
                    $data['loteria'],
                ) 
            )
        );

        // Insere o post e retorna o ID ou WP_Error
        return wp_insert_post($args);
    }

}