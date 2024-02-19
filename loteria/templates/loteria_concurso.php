<div id="loteria_container">
  <p class="loteria_header" style="background-color:<?php echo $concursoColor; ?>;">
    Concurso <?php echo $concursoData['concurso']; ?> &bull;
    <?php
      $data = $concursoData["data"];
      $dataFormatada = date("Y-m-d", strtotime(str_replace('/', '-', $data)));
      $diaDaSemana = date('w', strtotime($dataFormatada));
      $diasDaSemana = array(
          0 => 'Domingo',
          1 => 'Segunda-Feira',
          2 => 'Terça-Feira',
          3 => 'Quarta-Feira',
          4 => 'Quinta-Feira',
          5 => 'Sexta-Feira',
          6 => 'Sábado'
      );

      $nomeDiaDaSemana = $diasDaSemana[$diaDaSemana];
    echo "{$nomeDiaDaSemana} {$concursoData['data']}"; ?></p>
  <div class="loteria_resultado">
    <ul>
      <?php
        foreach( $concursoData['dezenas'] as $d) {
          echo "<li style='background-color: {$concursoColor};'>$d</li>";
        }
        ?>
    </ul>
  </div>
  <?php
    $totalPremios = 0;
    foreach ($concursoData['premiacoes'] as $premiacao) {
        $totalPremios += $premiacao['valorPremio'];
    }
  ?>
  <div class="loteria_premio">
    <p>PRÊMIO</p>
    <p>R$ <?php echo number_format($totalPremios, 2, ',', '.'); ?></p>
  </div>
  <div class="loteria_vencedores">
    <table>
      <thead>
        <th style="color: <?php echo $concursoColor; ?>">Faixas</th>
        <th style="color: <?php echo $concursoColor; ?>">Ganhadores</th>
        <th style="color: <?php echo $concursoColor; ?>">Prêmio</th>
      </thead>
      <tbody>
        <?php foreach ($concursoData['premiacoes'] as $premiacao) { ?>
          <tr>
            <td>
              <?php
                switch ($premiacao['faixa']) {
                  case '1':
                      echo 'Sena';
                    break;
                  case '2':
                      echo 'Quina';
                    break;
                  case '3':
                      echo 'Quadra';
                    break;

                  default:
                     echo $premiacao['faixa'];
                    break;
                }
              ?>
            </td>
            <td><?php echo $premiacao['ganhadores']; ?></td>
            <td>R$ <?php echo number_format($premiacao['valorPremio'], 2, ',', '.'); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
