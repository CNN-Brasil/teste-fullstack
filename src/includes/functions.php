<?php

// Função para formatar o número do concurso
function formatar_numero_concurso($numero)
{
  return number_format($numero, 0, ',', '.');
}

// Função para obter o dia da semana em português
function obter_dia_da_semana($data)
{
  $data_concurso = DateTime::createFromFormat('d/m/Y', $data);
  $dia_da_semana = $data_concurso->format('l'); // Obtém o dia da semana em inglês

  // Array de tradução dos dias da semana
  $dias_em_portugues = [
    'Sunday' => 'Domingo',
    'Monday' => 'Segunda-Feira',
    'Tuesday' => 'Terça-Feira',
    'Wednesday' => 'Quarta-Feira',
    'Thursday' => 'Quinta-Feira',
    'Friday' => 'Sexta-Feira',
    'Saturday' => 'Sábado',
  ];

  return $dias_em_portugues[$dia_da_semana] ?? $dia_da_semana; // Retorna o dia em português
}
