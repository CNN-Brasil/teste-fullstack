<?php
// Função para criar o shortcode
function loteriasShortcode($atts) {

    $atts = shortcode_atts(array(
        'concurso' => 'ultimo',
        'loteria' => 'megasena',
    ), $atts);

    $concurso = $atts['concurso'];
    $loteria = $atts['loteria'];

    // Verifica se o concurso já está no banco de dados
    $query_args = array(
        'post_type' => 'loterias',
        'meta_query' => array(
            array(
                'key' => 'concurso_numero',
                'value' => $concurso,
                'compare' => '='
            ),
            array(
                'key' => 'loteria_tipo',
                'value' => $loteria,
                'compare' => '='
            ),
        ),
    );

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $resultados = get_post_meta(get_the_ID(), 'resultados', true);
            return renderResults($resultados);
        }
    } else {
        if ($concurso == 'ultimo') {
            $concurso = 'latest';
        }

        $resultados = lotteryResults($loteria, $concurso);

        if ($resultados) {
            $post_id = wp_insert_post(array(
                'post_title' => 'Concurso ' . $resultados['concurso'],
                'post_type' => 'loterias',
                'post_status' => 'publish',
            ));

            update_post_meta($post_id, 'loteria_tipo', $loteria);
            update_post_meta($post_id, 'concurso_numero', $resultados['concurso']);
            update_post_meta($post_id, 'resultados', $resultados);

            return renderResults($resultados);
        } else {
            return 'Erro ao buscar resultados.';
        }
    }

    wp_reset_postdata();
}
add_shortcode('loteria_resultado', 'loteriasShortcode');
?>
