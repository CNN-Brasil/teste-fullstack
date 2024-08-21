<?php

// Fechar se acessado diretamente
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="<?php echo esc_html($results['loteria']); ?>" class="card-lottery">
    <div class="card-lottery__header">
        <h3 class="card-lottery__title">
            <?php echo 'Concurso ' . esc_html($results['concurso']) . ' • ' . esc_html($formatted_date); ?>
        </h3>
    </div>

    <div class="card-lottery__body">
        <div class="card-lottery__dozens">
            <ul class="card-lottery__dozens-list">
                <?php foreach ($results['dezenas'] as $dozen) : ?>
                    <li class="card-lottery__dozen"><?php echo esc_html($dozen); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-lottery__awards">
            <h4 class="card-lottery__awards-title">Prêmio</h4>
            <p class="card-lottery__awards-value">
                <?php
                $premio_principal = $results['valorEstimadoProximoConcurso'];
                echo 'R$ ' . number_format($premio_principal, 2, ',', '.');
                ?>
            </p>
        </div>

        <div class="card-lottery__winners">
            <table class="card-lottery__winners-table">
                <thead>
                    <tr>
                        <th>Faixas</th>
                        <th>Ganhadores</th>
                        <th>Prêmio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['premiacoes'] as $premiacao) : ?>
                        <tr>
                            <td><?php echo esc_html($premiacao['descricao']); ?></td>
                            <td><?php echo esc_html($premiacao['ganhadores']); ?></td>
                            <td><?php echo 'R$ ' . number_format($premiacao['valorPremio'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>