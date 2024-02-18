<?php

/**
 * Template do shortcode [loterias]
 */

$loteria = $results['loteria'];
$concurso = $results['concurso'];
$data_concurso = $results['data'];
$numeros_sorteados = $results['dezenasOrdemSorteio'];
$valorPremio = converterDecimal($results['valorArrecadado']);
$premiacoes = $results['premiacoes'];



function converterDecimal($numeroCientifico)
{
  $numeroDecimal = floatval($numeroCientifico);
  return number_format($numeroDecimal, 2, ',', '.');
}
function formatarNumeroSemDecimal($numero)
{
  return number_format($numero, 0, ',', '.');
}




wp_enqueue_style('loterias-shortcode', plugin_dir_url(__FILE__) . '../templates/css/shortcode-loterias.css');


?>

<section class="grid grid-auto-rows-2">
  <div class="item item-2 <?= $loteria ?>">Concurso 1.000 • Quarta-Feira 20/04/2017</div>

  <?php foreach ($numeros_sorteados as $numero) : ?>
    <div class="item">
      <div class="circulo <?= $loteria ?>"><?= $numero; ?></div>
    </div>
  <?php endforeach; ?>


</section>
<hr />
<section class="grid grid-auto-rows-6">
  <div class="item item-2 premio-color">Prêmio <br />R$ <?= $valorPremio ?></div>
</section>
<hr />
<section class="grid grid-auto-rows-3">
  <div class="item text-<?= $loteria ?>">Faixas</div>
  <div class="item text-<?= $loteria ?>">Ganhadores</div>
  <div class="item text-<?= $loteria ?>">Prêmio</div>
</section>
<hr />
<?php foreach ($premiacoes as $i => $premiacao) : ?>
  <section class="grid grid-auto-rows-3">
    <div class="item premio-color"><?= $premiacao['descricao'] ?></div>
    <div class="item premio-color"><?= formatarNumeroSemDecimal($premiacao['ganhadores']) ?></div>
    <div class="item premio-color">R$ <?= converterDecimal($premiacao['valorPremio']) ?></div>
  </section>

  <?php if (count($premiacoes) != $i + 1) {
    echo '<hr />';
  } ?>


<?php endforeach; ?>