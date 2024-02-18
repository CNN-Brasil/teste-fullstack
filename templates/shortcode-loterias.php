<?php

/**
 * Template do shortcode [loterias]
 */

 if (!function_exists('converterDecimal')) {
  function converterDecimal($numeroCientifico)
  {
      $numeroDecimal = floatval($numeroCientifico);
      return number_format($numeroDecimal, 2, ',', '.');
  }
}

if (!function_exists('formatarNumeroSemDecimal')) {
  function formatarNumeroSemDecimal($numero)
  {
      return number_format($numero, 0, ',', '.');
  }
}

$loteria = $results['loteria'];
$concurso = $results['concurso'];
$data_concurso = $results['data'];
$numeros_sorteados = $results['dezenasOrdemSorteio'];
$valorPremio = converterDecimal($results['valorArrecadado']);
$premiacoes = $results['premiacoes'];

wp_enqueue_style('loterias-shortcode', plugin_dir_url(__FILE__) . '../templates/css/shortcode-loterias.css');

?>

<section class="grid grid-auto-rows-2">
  <div class="item item-2 <?php echo esc_html($loteria); ?>">Concurso 1.000 • Quarta-Feira 20/04/2017</div>

  <?php foreach ($numeros_sorteados as $numero) : ?>
      <div class="item">
          <div class="circulo <?php echo esc_html($loteria); ?>"><?php echo esc_html($numero); ?></div>
      </div>
  <?php endforeach; ?>
</section>

<hr />

<section class="grid grid-auto-rows-6">
  <div class="item item-2 premio-color">PRÊMIO <br />R$ <?php echo esc_html($valorPremio); ?></div>
</section>

<hr class="premiohr" />

<section class="grid grid-auto-rows-3">
  <div class="item text-<?php echo esc_html($loteria); ?>">Faixas</div>
  <div class="item text-<?php echo esc_html($loteria); ?>">Ganhadores</div>
  <div class="item text-<?php echo esc_html($loteria); ?>">Prêmio</div>
</section>

<hr />

<?php foreach ($premiacoes as $i => $premiacao) : ?>
  <section class="grid grid-auto-rows-3">
      <div class="item premio-color-black"><?php echo esc_html($premiacao['descricao'])?></div>
      <div class="item premio-color-black"><?php echo esc_html(formatarNumeroSemDecimal($premiacao['ganhadores'])) ?></div>
      <div class="item premio-color-black">R$ <?php echo esc_html($premiacao['valorPremio']) ?></div>
  </section>

  <?php if (count($premiacoes) != $i + 1) {
      echo '<hr />';
  } ?>

<?php endforeach; ?>
