<?php
namespace LoteriasPlugin;

class LoteriasInsertPost {

    public function add($data) {

        $args = array(
            'post_title' => wp_strip_all_tags($data['loteria'].' concurso '.$data['concurso']. ' - '.$data['data']),
            'post_type' => 'loterias',
            'post_status' => 'publish',
            'post_content'  => json_encode($data),
            'meta_input' => array(
                'concurso' => $data['concurso']
            ),
            'tax_input' => array(
                'tipo_concurso' => array(
                    $data['loteria'],
                ) 
            )
        );

        return (wp_insert_post($args));
    }

}