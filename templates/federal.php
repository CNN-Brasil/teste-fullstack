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

  <table class="tabela-premiacao">
    <thead>
      <tr>
        <th>Posição</th>
        <th>Bilhete</th>
        <th>Prêmio (R$)</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $posicoes = ['1º Prêmio', '2º Prêmio', '3º Prêmio', '4º Prêmio', '5º Prêmio']; // Defina as posições conforme a necessidade
      $dezenas = isset($data['dezenas']) ? $data['dezenas'] : [];
      $premiacoes = isset($data['premiacoes']) ? $data['premiacoes'] : [];

      // Verifica se o número de premiacoes é igual ao número de dezenas
      if (count($dezenas) === count($premiacoes)) {
        foreach ($premiacoes as $index => $premiacao) {
          // Usa a posição definida acima
          $posicao = isset($posicoes[$index]) ? $posicoes[$index] : '';

          // Obtém o bilhete correspondente da variável dezenas
          $bilhete = isset($dezenas[$index]) ? $dezenas[$index] : '';

          // Obtém o valor do prêmio
          $valorPremio = isset($premiacao['valorPremio']) ? number_format($premiacao['valorPremio'], 2, ',', '.') : '0,00';
      ?>
      <tr>
        <td><?php echo esc_html($posicao); ?></td>
        <td><?php echo esc_html($bilhete); ?></td>
        <td>R$ <?php echo esc_html($valorPremio); ?></td>
      </tr>
      <?php
        }
      }
      ?>
    </tbody>
  </table>
</div>
