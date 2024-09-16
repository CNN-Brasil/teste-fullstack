<?php
// Função para renderizar os resultados no front-end
function renderResults($resultados) {

    $loteria_nome = strtolower(str_replace(' ', '-', $resultados['loteria']));

    ob_start();
    ?>
    <div class="container">
        <div class="header">
            <h1 class="<?php echo esc_attr($loteria_nome); ?>">Concurso <?php echo $resultados['concurso']; ?> • <?php echo $resultados['data']; ?></h1>
        </div>
        <div class="numbers">
            <?php foreach ($resultados['dezenasOrdemSorteio'] as $key => $value) { ?>
                <div class="number <?php echo esc_attr($loteria_nome); ?>"><?= $value; ?></div>
            <?php } ?>
        </div>
        <hr>
        <div class="prize">
            <h2>PRÊMIO</h2>
            <p>R$ <?php echo number_format($resultados['valorEstimadoProximoConcurso'], 2, ',', '.'); ?></p>
        </div>
        <div class="results">
            <table>
                <thead>
                    <tr>
                        <th class="<?php echo esc_attr($loteria_nome); ?>">Faixas</th>
                        <th class="<?php echo esc_attr($loteria_nome); ?>">Ganhadores</th>
                        <th class="<?php echo esc_attr($loteria_nome); ?>">Prêmio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados['premiacoes'] as $key => $value) { ?>
                        <tr>
                            <td><?= $value['faixa']; ?></td>
                            <td><?= $value['ganhadores']; ?></td>
                            <td>R$ <?= number_format($value['valorPremio'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>
