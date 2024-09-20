<?php

namespace LoteriasCaixa\includes;

class Loteria_Front
{

	//Define a cor de acordo com a loteria. 
	private function select_color($loteria)
	{
		$colors = array(
			'maismilionaria' => 'color-maismilionaria',
			'megasena'       => 'color-megasena',
			'lotofacil'      => 'color-lotofacil',
			'quina'          => 'color-quina',
			'lotomania'      => 'color-lotomania',
			'timemania'      => 'color-timemania',
			'duplasena'      => 'color-duplasena',
			'federal'        => 'color-federal',
			'diadesorte'     => 'color-diadesorte',
			'supersete'      => 'color-supersete',
		);

		return isset($colors[$loteria]) ? $colors[$loteria] : 'color-padrao';
	}

	//Define a cor do background acordo com a loteria.
	private function select_color_background($loteria)
	{
		$background = array(
			'maismilionaria' => 'back-maismilionaria',
			'megasena'       => 'back-megasena',
			'lotofacil'      => 'back-lotofacil',
			'quina'          => 'back-quina',
			'lotomania'      => 'back-lotomania',
			'timemania'      => 'back-timemania',
			'duplasena'      => 'back-duplasena',
			'federal'        => 'back-federal',
			'diadesorte'     => 'back-diadesorte',
			'supersete'      => 'back-supersete',
		);

		return isset($background[$loteria]) ? $background[$loteria] : 'back-padrao';
	}

	public function __construct()
	{
		add_shortcode('loterias', array($this, 'exibe_loteria'));
	}

	public function exibe_loteria($atts)
	{
		return exibir_resultados_loterias($atts);
	}

	public function formatar_resultados_html($resultados)
	{

		$cor = $this->select_color($resultados['loteria']);

		$back_color = $this->select_color_background($resultados['loteria']);

		$html = "<div class='loteria'>";
		$html .= "<div class='header {$back_color}'> {$resultados['loteria']} - Concurso {$resultados['concurso']} - {$resultados['data']}</div>";
		$html .= "<div class='sorteio'>";
		$html .= "<ul class='sorteio-lista'>";
		foreach ($resultados['dezenas'] as $dezena) {
			$html .= "<li class='$back_color'>{$dezena}</li>";
		}
		$html.= '</ul>';
		$html.= '</div>';
		$html.= "<hr class='hr-linha'>";
		$html.= "<div class='valores'>";
		$html.= '<p>PRÊMIO</p>';
		$valor= $resultados['valorEstimadoProximoConcurso'];
		$valor_formatado = number_format($valor, 2, ',', '.');
		$html.= "<p>R$ {$valor_formatado}</p>";
		$html.= '</div>';
		$html.= "<hr class='hr-linha'>";
		$html.= '<table>';
		$html.= "<tr><th class='{$cor}'>Faixas</th><th class='{$cor}'>Ganhadores</th><th class='{$cor}'>Prêmio</th></tr>";
		foreach ($resultados['premiacoes'] as $premiacao) {
			$html .= "<tr><td>{$premiacao['descricao']}</td><td>{$premiacao['ganhadores']}</td><td>R$ {$premiacao['valorPremio']}</td></tr>";
		}
		$html .= '</table>';
		$html .= '</div>';

		return $html;
	}
}

new Loteria_Front();
