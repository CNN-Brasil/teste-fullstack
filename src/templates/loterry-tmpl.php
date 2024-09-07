<div class="wrapper <?php echo esc_html($data['loteria']); ?>">
    <div class="container">
        <div class="lottery">
            <div class="lottery__info">
                Concurso <?php echo esc_html($data['concurso']); ?> - <?php echo esc_html($data['data']); ?>
            </div>
        </div>
    </div>
</div>

<section class="lottery__numbers__wrapper">
    <div class="container">
        <div class="lottery__numbers">
            <?php foreach ($data['dezenas'] as $key => $value) { ?>
                <span class="<?php echo esc_html($data['loteria']); ?>"><?= $value; ?></span>
            <?php } ?>
        </div>
    </div>
</section>

<section class="lottery__premium__wrapper">
    <div class="container lottery__premium">
        <div class="lottery__premium__title">prêmio</div>
        <div class="lottery__premium__cash"> R$ <?= number_format($data['valorAcumuladoConcurso_0_5'], 2, ",", "."); ?></div>
    </div>
</section>

<section class="container">
    <div class="lottery__winners">
        <div class="lottery__winners__header">
            <span class="title-<?php echo esc_html($data['loteria']); ?>">Faixas</span>
            <span class="title-<?php echo esc_html($data['loteria']); ?>">Ganhadores</span>
            <span class="title-<?php echo esc_html($data['loteria']); ?>">Prêmio</span>
        </div>
        <div class="lottery__winners__body">
            <?php foreach ($data['premiacoes'] as $key => $value) { ?>
                <div class="lottery__winners__body__results">
                    <span><?= $value['descricao']; ?></span>
                    <span><?= $value['ganhadores']; ?></span>
                    <span>R$ <?= number_format($value['valorPremio'], 2, ",", "."); ?> </span>
                </div>
            <?php } ?>
        </div>
    </div>
</section>