<?php
namespace LoteriasPlugin;

class LoteriasCheckPost {
    
    public function check($data) {

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
        $result = new \WP_Query($args);

        if(count($result->posts) !== 0) {

            return $result->posts[0];
        
        } 

        return false;

    }

}