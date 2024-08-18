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
    <?php if ($data['acumulou']): ?>
      <p class="acumulou">
        Acumulou! O prêmio estimado para o próximo concurso é de R$
        <?php echo esc_html(number_format($data['valorEstimadoProximoConcurso'], 2, ',', '.')); ?>
      </p>
    <?php else: ?>
      <p>
        R$
        <?php
          // Obtenha o valor do prêmio para a maior faixa (7 acertos, por exemplo)
          $valorPremio = isset($data['premiacoes'][0]['valorPremio']) ? $data['premiacoes'][0]['valorPremio'] : 0;
          echo esc_html(number_format($valorPremio, 2, ',', '.'));
        ?>
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
    $mesSorte = $data['mesSorte']; // Acessa o mês da sorte
  
    foreach ($data['premiacoes'] as $premiacao) {
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
  
    <?php if ($mesSorte): // Exibe o Mês da Sorte no final ?>
        <tr>
            <td colspan="3">
                <strong>Mês da Sorte:</strong> <?php echo esc_html($mesSorte); ?>
            </td>
        </tr>
    <?php endif; ?>
</tbody>   
</table>
</div>