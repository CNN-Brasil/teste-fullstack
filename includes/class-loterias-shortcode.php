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

        // Certificar que o resultado é um array, para evitar codificação duplicada
        if (!is_array($resultado)) {
            $resultado = json_decode($resultado, true); // Decodificar caso esteja em formato string JSON
        }

        // Iniciar o buffer de saída
        ob_start();
        ?>
        <div class="loteria-resultado">
            <h2>Resultado da Loteria: <?php echo esc_html($resultado['loteria']); ?></h2>
            <p><strong>Concurso nº:</strong> <?php echo esc_html($resultado['concurso']); ?></p>
            <p><strong>Data:</strong> <?php echo esc_html($resultado['data']); ?></p>
            <p><strong>Local:</strong> <?php echo esc_html($resultado['local']); ?></p>
            <p><strong>Dezenas sorteadas:</strong> <?php echo implode(', ', $resultado['dezenasOrdemSorteio']); ?></p>
        </div>
        <?php

        return ob_get_clean(); // Retornar o conteúdo bufferizado
    }
}

new Loterias_Shortcode();


