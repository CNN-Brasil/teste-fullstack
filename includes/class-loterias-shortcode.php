<?php
class Loterias_Shortcode {

    public function __construct() {
        add_shortcode('loteria_resultado', array($this, 'render_shortcode'));
    }

    function obterDiaSemana($data) {
        $dataFormatada = DateTime::createFromFormat('d/m/Y', $data);
        if ($dataFormatada) {
            
            $diasDaSemana = [
                'Sunday' => 'Domingo',
                'Monday' => 'Segunda-feira',
                'Tuesday' => 'Terça-feira',
                'Wednesday' => 'Quarta-feira',
                'Thursday' => 'Quinta-feira',
                'Friday' => 'Sexta-feira',
                'Saturday' => 'Sábado'
            ];

            $diaSemanaIngles = $dataFormatada->format('l');
            return $diasDaSemana[$diaSemanaIngles] ?? $diaSemanaIngles;
        }

        return false;
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
        <div class="loterias-caixa">
        <div class="card-header color-theme <?php echo esc_html($resultado['loteria']); ?>">
            Concurso <?php echo esc_html($resultado['concurso']); ?> • 
            <?php echo esc_html($this->obterDiaSemana($resultado['data']) ?: 'Data inválida'); ?> 
            <?php echo esc_html($resultado['data']); ?>
        </div>
            <div class="card-dezenas">
                <ul>
                    <?php foreach ($resultado['dezenas'] as $dezena): ?>
                        <li class="color-theme <?php echo esc_html($resultado['loteria']); ?>">
                            <?php echo esc_html($dezena); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-premio">
                <p>Prêmio</p>
                R$ <?php echo number_format($resultado['valorEstimadoProximoConcurso'], 2, ',', '.'); ?>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="color-theme <?php echo esc_html($resultado['loteria']); ?>">Faixas</th>
                        <th class="color-theme <?php echo esc_html($resultado['loteria']); ?>">Ganhadores</th>
                        <th class="color-theme <?php echo esc_html($resultado['loteria']); ?>">Prêmio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado['premiacoes'] as $premiacao): ?>
                        <tr>
                            <td><?php echo esc_html($premiacao['descricao']); ?></td>
                            <td><?php echo esc_html($premiacao['ganhadores']); ?></td>
                            <td>R$ <?php echo number_format($premiacao['valorPremio'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean(); // Retornar o conteúdo bufferizado
    }
}

new Loterias_Shortcode();



