<?php
class Loterias_Shortcode {

    public function __construct() {
        add_shortcode('loteria_resultado', array($this, 'render_shortcode'));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'loteria'  => 'megasena',
            'concurso' => 'ultimo',
        ), $atts);

        $api = new Loterias_API();
        $resultado = $api->buscar_resultado($atts['loteria'], $atts['concurso']);

        if (!$resultado) {
            return '<p>Erro ao buscar o resultado da loteria.</p>';
        }

        // Verificar se o concurso já existe no CPT 'Loterias'
        $existing_post = get_posts(array(
            'post_type' => 'loterias',
            'meta_query' => array(
                array(
                    'key' => 'concurso',
                    'value' => $resultado['concurso'],
                    'compare' => '='
                )
            )
        ));

        // Se o concurso não existe, criar um novo post no CPT 'Loterias'
        if (empty($existing_post)) {
            $post_id = wp_insert_post(array(
                'post_title'   => $resultado['loteria'] . ' - Concurso ' . $resultado['concurso'],
                'post_type'    => 'loterias',
                'post_status'  => 'publish',
            ));

            // Salvar os metadados associados
            if ($post_id) {
                update_post_meta($post_id, 'loteria', $resultado['loteria']);
                update_post_meta($post_id, 'concurso', $resultado['concurso']);
                update_post_meta($post_id, 'data', $resultado['data']);
                update_post_meta($post_id, 'dezenasOrdemSorteio', implode(', ', $resultado['dezenasOrdemSorteio']));
            }
        }

        // Exibir os resultados no front-end
        ob_start();
        ?>
        <div class="loteria-resultado">
            <h2>Resultado da Loteria: <?php echo esc_html($resultado['loteria']); ?></h2>
            <p><strong>Concurso nº:</strong> <?php echo esc_html($resultado['concurso']); ?></p>
            <p><strong>Data:</strong> <?php echo esc_html($resultado['data']); ?></p>
            <p><strong>Local:</strong> <?php echo esc_html($resultado['local']); ?></p>
            <p><strong>Dezenas sorteadas:</strong> <?php echo implode(', ', $resultado['dezenasOrdemSorteio']); ?></p>

            <h3>Premiações</h3>
            <ul>
                <?php foreach ($resultado['premiacoes'] as $premiacao): ?>
                    <li>
                        <strong><?php echo esc_html($premiacao['descricao']); ?>:</strong>
                        <?php echo esc_html($premiacao['ganhadores']); ?> ganhador(es), prêmio de R$ <?php echo number_format($premiacao['valorPremio'], 2, ',', '.'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3>Próximo Concurso</h3>
            <p><strong>Concurso nº:</strong> <?php echo esc_html($resultado['proximoConcurso']); ?></p>
            <p><strong>Data:</strong> <?php echo esc_html($resultado['dataProximoConcurso']); ?></p>
            <p><strong>Prêmio Estimado:</strong> R$ <?php echo number_format($resultado['valorEstimadoProximoConcurso'], 2, ',', '.'); ?></p>

            <p><strong>Acumulou?</strong> <?php echo $resultado['acumulou'] ? 'Sim' : 'Não'; ?></p>
        </div>
        <?php

        return ob_get_clean(); // Retornar o conteúdo bufferizado
    }
}

new Loterias_Shortcode();



