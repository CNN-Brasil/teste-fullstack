<?php

if (!isset($results) || empty($results)) {
    echo "Nenhum resultado disponível.";
    return; 
}
?>
<div class="container">
    <div class="header">
        Concurso <?= esc_html($results['concurso']) ?> • <?= esc_html(date('d/m/Y', strtotime($results['data']))) ?>
    </div>
    <div class="numbers">
        <?php foreach ($results['dezenas'] as $numero): ?>
            <div class="number"><?= esc_html($numero) ?></div>
        <?php endforeach; ?>
    </div>
    <div class="prize">
        Prêmio<br>
        R$ <?= number_format($results['premiacoes'][0]['valorPremio'], 2, ',', '.') ?>
    </div>
    <table>
        <tr>
            <th>Faixas</th>
            <th>Ganhadores</th>
            <th>Prêmio</th>
        </tr>
        <?php foreach ($results['premiacoes'] as $premiacao): ?>
        <tr>
            <td><?= esc_html($premiacao['descricao']) ?></td>
            <td><?= number_format($premiacao['ganhadores']) ?></td>
            <td>R$ <?= number_format($premiacao['valorPremio'], 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="footer">
    <div style="background-color: #4CAF50; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Mega Sena
    <div style="background-color: #0000FF; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Quina
    <div style="background-color: #8e44ad; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Loto Fácil
    <div style="background-color: #f39c12; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Loto Mania
    <div style="background-color: #3498db; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Time Mania
    <div style="background-color: #e67e22; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Dupla Sena
    <div style="background-color: #e74c3c; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Federal
    <div style="background-color: #2ecc71; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Dia de Sorte
    <div style="background-color: #c0392b; width: 20px; height: 20px; display: inline-block; margin-right: 10px;"></div> Super Sete
</div>
</div>