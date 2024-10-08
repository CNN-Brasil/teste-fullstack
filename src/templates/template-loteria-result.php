<?php if ($result) : ?>
  <div class="loteria-result loteria-<?= esc_attr($result['loteria']); ?>">
    <!-- Cabeçalho com o número do concurso e data -->
    <h3>
      Concurso <?= esc_html(formatar_numero_concurso($result['concurso'])); ?> • <?= esc_html(obter_dia_da_semana($result['data']) . ' ' . $result['data']); ?>
    </h3>

    <!-- Dezenas sorteadas -->
    <div class="dezenas-container">
      <?php foreach ($result['dezenas'] as $dezena) : ?>
        <div class="dezena"><?= esc_html($dezena); ?></div>
      <?php endforeach; ?>
    </div>

    <!-- Valor total do prêmio -->
    <div class="premio">
      <span class="text-premio">PRÊMIO</span>
      <span class="valor-premio">R$ <?= esc_html(number_format($result['premiacoes'][0]['valorPremio'], 2, ',', '.')); ?><span class="text-premio"></span>
    </div>

    <!-- Tabela de premiações -->
    <table>
      <thead>
        <tr>
          <th>Faixas</th>
          <th>Ganhadores</th>
          <th>Prêmio (R$)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result['premiacoes'] as $premiacao) : ?>
          <tr>
            <td>
              <?php
              if ($result['loteria'] === 'megasena') :
                // Ajusta a descrição das faixas de premiação para Sena, Quina e Quadra
                switch ($premiacao['faixa']) {
                  case 1:
                    echo esc_html('Sena'); // Primeira faixa (6 acertos)
                    break;
                  case 2:
                    echo esc_html('Quina'); // Segunda faixa (5 acertos)
                    break;
                  case 3:
                    echo esc_html('Quadra'); // Terceira faixa (4 acertos)
                    break;
                  default:
                    echo esc_html($premiacao['descricao']); // Caso existam outras faixas não especificadas
                    break;
                }
              else:
                echo esc_html($premiacao['descricao']);
              endif;
              ?>
            </td>
            <td>
              <?= esc_html(number_format($premiacao['ganhadores'], 0, ',', '.')); ?>
            </td>
            <td>
              <?= esc_html('R$ ' . number_format($premiacao['valorPremio'], 2, ',', '.')); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>