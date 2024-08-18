<div
  class="resultado-loteria loteria-<?php echo esc_attr($data['loteria']); ?> cor-theme-<?php echo esc_attr($data['loteria']); ?>"
>
  <div class="cabecalho">
    <div class="info-content">
      <span class="concurso"
        >Concurso
        <?php echo esc_html($data['concurso'] === 'latest' ? 'último' : $data['concurso']); ?></span
      >
      <span class="separator">•</span>
      <span class="date"
        ><?php echo esc_html(date_i18n('l, d/m/Y', strtotime($data['data']))); ?></span
      >
    </div>
  </div>

  <div class="dezenas-sorteadas">
    <?php if (isset($data['dezenas']) && is_array($data['dezenas'])): ?>
    <?php foreach ($data['dezenas'] as $dezena): ?>
    <div class="dezena"><?php echo esc_html($dezena); ?></div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="dezena indisponivel">Não disponível</div>
    <?php endif; ?>
  </div>

  <div class="premio">
  <strong>PRÊMIO</strong>
  <?php
  $valorEstimadoProximoConcurso = isset($data['valorEstimadoProximoConcurso']) ? $data['valorEstimadoProximoConcurso'] : 0;
  $valorPremio = 0;
  $faixa = '';

  // Verifica se o prêmio está presente nas premiações
  if (isset($data['premiacoes']) && is_array($data['premiacoes'])) {
    foreach ($data['premiacoes'] as $premiacao) {
      // Verifica se a faixa é '6 acertos' e atribui o valor do prêmio
      if ($premiacao['descricao'] === '6 acertos') {
        $faixa = $premiacao['descricao'];
        $valorPremio = $premiacao['valorPremio'];
        break; // Encerra o loop após encontrar o valor de '6 acertos'
      }
    }
  }

  // Exibe a mensagem com base no valor do prêmio
  if ($faixa === '6 acertos' && isset($data['premiacoes']) && $data['premiacoes'][0]['ganhadores'] == 0): ?>
    <p class="acumulou">
      Acumulou! O prêmio estimado para o próximo concurso é de R$
      <?php echo esc_html(number_format($valorEstimadoProximoConcurso, 2, ',', '.')); ?>
    </p>
  <?php else: ?>
    <p>
      R$
      <?php echo esc_html(number_format($valorPremio, 2, ',', '.')); ?>
    </p>
  <?php endif; ?>
</div>

<table class="tabela-premiacao">
  <thead>
    <tr>
      <th>Faixas</th>
      <th>Ganhadores</th>
      <th>Prêmio</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($data['premiacoes'] as $premiacao) {
      // Exibe a descrição da faixa exatamente como vem da API
      $faixa = $premiacao['descricao'];
    ?>
    <tr>
      <td><?php echo esc_html($faixa); ?></td>
      <td>
        <?php echo isset($premiacao['ganhadores']) ? esc_html($premiacao['ganhadores']) : '0'; ?>
      </td>
      <td>
        R$
        <?php echo isset($premiacao['valorPremio']) ? esc_html(number_format($premiacao['valorPremio'], 2, ',', '.')) : '0,00'; ?>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>