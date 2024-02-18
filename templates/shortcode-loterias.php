<?php

/**
 * Template do shortcode [loterias]
 */

$loteria = $results['loteria'];
$concurso = $results['concurso'];
$data_concurso = $results['data'];
$numeros_sorteados = $results['dezenasOrdemSorteio'];



?>

<h2>Resultados da Loteria <?php echo $loteria; ?></h2>
<p>Concurso: <?php echo $concurso; ?></p>
<p>Data: <?php echo $data_concurso; ?></p>
<ul>
  <?php foreach ($numeros_sorteados as $numero) : ?>
    <li><?php echo $numero; ?></li>
  <?php endforeach; ?>
</ul>