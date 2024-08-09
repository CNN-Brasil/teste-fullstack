<?php

/**
 * Internal Loterias
 *
 * @package Loterias
 */

/*
Plugin Name: Loterias
Plugin URI: https://github.com/ctoveloz/teste-fullstack
Description: Loterias Tigrin
Version: 1.0.0
Author: Cristiano Matos
License: MIT
Copyright: Copyright (c) 2024, Cristiano Matos
*/

if (!defined('ABSPATH')) {
    exit;
}

setlocale(LC_TIME, 'pt_BR.UTF-8', 'Portuguese_Brazil');
$decoded_data = json_decode($data, true);

if (!$decoded_data || !isset($decoded_data['data'])) {
    echo '<div class="error">Dados inválidos fornecidos.</div>';
    return;
}

$date_string = $decoded_data['data'];
$date_object = DateTime::createFromFormat('d/m/Y', $date_string);
$weekday = strftime('%A', $date_object->getTimestamp());

$weekday_replacements = [
    'segunda' => 'Segunda-Feira',
    'terça' => 'Terça-Feira',
    'quarta' => 'Quarta-Feira',
    'quinta' => 'Quinta-Feira',
    'sexta' => 'Sexta-Feira',
    'sábado' => 'Sábado',
    'domingo' => 'Domingo',
];

$weekday_lower = strtolower($weekday);
$weekday_adjusted = $weekday_replacements[$weekday_lower] ?? ucfirst($weekday_lower);

$formatted_date = ucfirst(strftime('%A, %d/%m/%Y', $date_object->getTimestamp()));
$formatted_date = str_replace(ucfirst($weekday_lower), $weekday_adjusted, $formatted_date);
$formatted_date = str_replace(',', '', $formatted_date);

$background_class = match ($decoded_data['loteria'] ?? 'default') {
    'megasena' => 'bg-megasena',
    'quina' => 'bg-quina',
    'lotofacil' => 'bg-lotofacil',
    'lotomania' => 'bg-lotomania',
    'duplasena' => 'bg-duplasena',
    'federal' => 'bg-federal',
    'diadesorte' => 'bg-diadesorte',
    'supersete' => 'bg-supersete',
    default => 'bg-default',
};

$color_class = match ($decoded_data['loteria'] ?? 'default') {
    'megasena' => 'color-megasena',
    'quina' => 'color-quina',
    'lotofacil' => 'color-lotofacil',
    'lotomania' => 'color-lotomania',
    'duplasena' => 'color-duplasena',
    'federal' => 'color-federal',
    'diadesorte' => 'color-diadesorte',
    'supersete' => 'color-supersete',
    default => 'color-default',
};

$faixa_replacements = [
    '6 acertos' => 'Sena',
    '5 acertos' => 'Quina',
    '4 acertos' => 'Quadra',
    '3 acertos' => 'Trevo',
    '2 acertos' => 'Dupla',
];

$premio_valor = '0,00';
if (isset($decoded_data['premiacoes'][0])) {
    $primeira_premiacao = $decoded_data['premiacoes'][0];
    if (isset($primeira_premiacao['ganhadores']) && $primeira_premiacao['ganhadores'] > 0) {
        $valor_total_premio = (isset($primeira_premiacao['valorPremio']) ? $primeira_premiacao['valorPremio'] : 0) * $primeira_premiacao['ganhadores'];
        $premio_valor = 'R$ ' . number_format($valor_total_premio, 2, ',', '.');
    } else {
        $premio_valor = 'Acumulou';
    }
}
?>

<div class="loteria-resultado grid-container">
    <header class="<?php echo esc_html($background_class); ?> <?php echo esc_html($color_class); ?>">
        <h2>Concurso <?php echo esc_html($decoded_data['concurso']); ?> • <?php echo esc_html($formatted_date); ?></h2>
    </header>
    <ul class="sorteadas">
        <?php if (isset($decoded_data['dezenas']) && is_array($decoded_data['dezenas'])) : ?>
            <?php foreach ($decoded_data['dezenas'] as $dezena) : ?>
                <li class="<?php echo esc_html($background_class); ?> <?php echo esc_html($color_class); ?>"><?php echo esc_html($dezena); ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <div class="premio">
        <h3 class="title">Prêmio</h3>
        <p class="valor"><?php echo esc_html($premio_valor); ?></p>
    </div>
    <div class="faixas-resultado">
        <table>
            <thead>
                <tr>
                    <th scope="col" class="<?php echo esc_html($color_class); ?>">Faixas</th>
                    <th scope="col" class="<?php echo esc_html($color_class); ?>">Ganhadores</th>
                    <th scope="col" class="<?php echo esc_html($color_class); ?>">Prêmio</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($decoded_data['premiacoes']) && is_array($decoded_data['premiacoes'])) : ?>
                    <?php foreach ($decoded_data['premiacoes'] as $premiacao) : ?>
                        <?php if (isset($premiacao['ganhadores']) && $premiacao['ganhadores'] > 0) : ?>
                            <tr>
                                <?php
                                $descricao = $faixa_replacements[$premiacao['descricao']] ?? $premiacao['descricao'];
                                ?>
                                <th scope="row"><?php echo esc_html($descricao); ?></th>
                                <td><?php echo esc_html($premiacao['ganhadores']); ?></td>
                                <td>R$ <?php echo esc_html(number_format($premiacao['valorPremio'], 2, ',', '.')); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
