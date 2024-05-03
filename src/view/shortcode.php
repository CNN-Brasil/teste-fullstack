<?php wp_enqueue_style('loterias-caixa-cnn-style', plugins_url('/css/style.css', __FILE__)); ?>
<div class="lcnn-content">
<section class="grid">
    <div class="item lcnn-bold white-colour <?= esc_html($loteria); ?>">Concurso <?= esc_html($this->loteria['concurso']) ?> • <?= esc_html($diaSemana) ?> <?= esc_html($data) ?></div>

    <div class="lcnn-content__sorted-numbers">
        <?php foreach ($dezenas as $numero) : ?>
            <div class="item">
                <div class="circulo <?= esc_html($this->loteria['loteria']); ?>"><?= esc_html($numero); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<hr />

<section class="grid grid-auto-rows-6">
    <div class="item item-2 premio-color">PRÊMIO <br />R$ <?= esc_html($this->loteria['valorEstimadoProximoConcurso']); ?></div>
</section>

<hr class="premio" />

<section class="grid grid-auto-rows-3">
    <div class="item lcnn-bold text-<?= esc_html($loteria); ?>">Faixas</div>
    <div class="item lcnn-bold text-<?= esc_html($loteria); ?>">Ganhadores</div>
    <div class="item lcnn-bold text-<?= esc_html($loteria); ?>">Prêmio</div>
</section>

<hr />

<?php foreach ($premiacoes as $i => $premiacao) : ?>
    <section class="grid grid-auto-rows-3">
        <div class="item premio-color-black"><?= esc_html($premiacao['descricao'])?></div>
        <div class="item premio-color-black"><?= esc_html($premiacao['ganhadores']) ?></div>
        <div class="item premio-color-black">R$ <?= esc_html(loteriasCaixaCNN_Utils::format_currency($premiacao['valorPremio'])) ?></div>
    </section>

    <?php if (count($premiacoes) != $i + 1) {
        echo '<hr />';
    } ?>

<?php endforeach; ?>

</div>
