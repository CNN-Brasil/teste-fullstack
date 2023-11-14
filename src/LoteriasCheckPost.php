<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasCheckPost - Responsável por verificar a existência de um post.
 */
class LoteriasCheckPost {
    
    /**
     * Verifica se um post existe com base nos dados fornecidos.
     *
     * @param array $data Dados para a verificação (loteria e concurso).
     * @return int|false ID do post existente ou false se não encontrado.
     */
    public function check($data) {

        // Argumentos para a consulta do WP_Query
        $args = array(
            'post_type' => 'loterias', 
            'fields' => 'ids',
            'tax_query' => array(
                array(
                    'taxonomy' => 'tipo_concurso',
                    'field' => 'slug',
                    'terms' => $data['loteria'],
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'concurso',
                    'value' => $data['concurso'],
                    'compare' => '=',
                )
            )
        );

        // Executa a consulta
        $result = new \WP_Query($args);

        // Verifica se há posts encontrados
        if(count($result->posts) !== 0) {

            // Retorna o ID do primeiro post encontrado
            return $result->posts[0];
        
        } 

        // Retorna false se nenhum post for encontrado
        return false;

    }

}