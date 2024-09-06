<?php

if (! defined('ABSPATH')) {
    exit;
}

?>

<div class="lottery-result <?php echo esc_attr($lottery_name); ?>">

    <div class="lottery-result__top">
        <span><?php echo esc_html('Concurso'); ?> <?php echo esc_html($contest); ?> • <?php echo esc_html($weekday) . ' ' . esc_html($date); ?></span>
    </div>

    <div class="lottery-result__numbers">
        <?php foreach ($numbers as $number) { ?>
            <span><?php echo esc_html($number); ?></span>
        <?php } ?>
    </div>

    <div class="lottery-result__award">
        <span><?php echo esc_html('Prêmio'); ?></span>
        <strong><?php echo esc_html('R$'); ?> <?php echo esc_html(number_format($collected_amount, 2, ',', '.')); ?></strong>
    </div>

    <?php if ($accumulated) { ?>
        <div class="lottery-result__accumulated">
            <span><?php echo esc_html('O prêmio acumulou'); ?></span>
        </div>
    <?php } ?>

    <div class="lottery-result__results">
        <div class="item">
            <strong><?php echo esc_html('Faixas'); ?></strong>
            <strong><?php echo esc_html('Ganhadores'); ?></strong>
            <strong><?php echo esc_html('Prêmio'); ?></strong>
        </div>
        <?php foreach ($prizes as $prize) { ?>
            <div class="item">
                <span><?php echo esc_html($prize['description']); ?></span>
                <span><?php echo esc_html($prize['winners']); ?></span>
                <span><?php echo esc_html('R$'); ?> <?php echo esc_html(number_format($prize['value'], 2, ',', '.')); ?></span>
            </div>
        <?php } ?>
    </div>

</div>