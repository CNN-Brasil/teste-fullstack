<?php
namespace Cnnbr\TesteFullstack\includes;

/**
 * Define o shortcode que exibe os resultados das loterias.
 *
 * @package LoteriasCaixa
 */

/**
 * Classe para gerenciar o shortcode das loterias.
 */
class Loterias_Shortcode {

	/**
	 * Determina a classe de cor baseada no tipo de loteria.
	 *
	 * @param string $loteria O identificador da loteria.
	 * @return string A classe de cor correspondente.
	 */
	private function get_loteria_cor_class( $loteria ) {
		$cores = array(
			'maismilionaria' => 'cor-maismilionaria',
			'megasena'       => 'cor-megasena',
			'lotofacil'      => 'cor-lotofacil',
			'quina'          => 'cor-quina',
			'lotomania'      => 'cor-lotomania',
			'timemania'      => 'cor-timemania',
			'duplasena'      => 'cor-duplasena',
			'federal'        => 'cor-federal',
			'diadesorte'     => 'cor-diadesorte',
			'supersete'      => 'cor-supersete',
		);

		return isset( $cores[ $loteria ] ) ? $cores[ $loteria ] : 'cor-default';
	}

	/**
	 * Determina a classe de background baseada no tipo de loteria.
	 *
	 * @param string $loteria O identificador da loteria.
	 * @return string A classe de background correspondente.
	 */
	private function get_loteria_bg_class( $loteria ) {
		$bg = array(
			'maismilionaria' => 'bg-maismilionaria',
			'megasena'       => 'bg-megasena',
			'lotofacil'      => 'bg-lotofacil',
			'quina'          => 'bg-quina',
			'lotomania'      => 'bg-lotomania',
			'timemania'      => 'bg-timemania',
			'duplasena'      => 'bg-duplasena',
			'federal'        => 'bg-federal',
			'diadesorte'     => 'bg-diadesorte',
			'supersete'      => 'bg-supersete',
		);

		return isset( $bg[ $loteria ] ) ? $bg[ $loteria ] : 'bg-default';
	}

	/**
	 * Construtor.
	 */
	public function __construct() {
		add_shortcode( 'loterias', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Renderiza o shortcode.
	 *
	 * @param array $atts Atributos do shortcode.
	 * @return string HTML do shortcode.
	 */
	public function render_shortcode( $atts ) {

		$api            = new Loterias_API();
		$resultado_json = $api->obter_dados_concurso( $atts['loteria'], $atts['concurso'] );
		$resultado      = json_decode( $resultado_json, true );

		if ( ! $resultado || isset( $resultado['erro'] ) ) {
			return 'Não foi possível obter os resultados. Por favor, tente novamente mais tarde.';
		}

		$cor_class = $this->get_loteria_cor_class( $atts['loteria'] );

		$bg_class = $this->get_loteria_bg_class( $atts['loteria'] );

		$html = "<div class='resultado-loteria'>";

		$data_api = $resultado['data'];

		$data_obj = \DateTime::createFromFormat( 'd/m/Y', $data_api );

		$formatter = new \IntlDateFormatter(
			'pt_BR',
			\IntlDateFormatter::FULL,
			\IntlDateFormatter::NONE,
			'America/Sao_Paulo',
			\IntlDateFormatter::GREGORIAN,
			'EEEE dd/MM/yyyy'
		);

		$data_formatada = $formatter->format( $data_obj );

		$html .= "<div class='cabecalho {$bg_class}'> Concurso {$resultado['concurso']} • {$data_formatada}</div>";

		$html .= "<div class='dezenas'>";
		$html .= "<ul class='dezenas-lista'>";
		foreach ( $resultado['dezenas'] as $dezena ) {
			$html .= "<li class='$bg_class'>{$dezena}</li>";
		}
		$html           .= '</ul>';
		$html           .= '</div>';
		$html           .= "<hr class='hr-linha'>";
		$html           .= "<div class='premiacao'>";
		$html           .= '<p>PRÊMIO</p>';
		$valor           = $resultado['valorEstimadoProximoConcurso'];
		$valor_formatado = number_format( $valor, 2, ',', '.' );
		$html           .= "<p>R$ {$valor_formatado}</p>";
		$html           .= '</div>';
		$html           .= "<hr class='hr-linha'>";
		$html           .= '<table>';
		$html           .= "<tr><th class='{$cor_class}'>Faixas</th><th class='{$cor_class}'>Ganhadores</th><th class='{$cor_class}'>Prêmio</th></tr>";
		foreach ( $resultado['premiacoes'] as $premiacao ) {
			$html .= "<tr><td>{$premiacao['descricao']}</td><td>{$premiacao['ganhadores']}</td><td>R$ {$premiacao['valorPremio']}</td></tr>";
		}
		$html .= '</table>';
		$html .= '</div>';

		return $html;
	}
}

new Loterias_Shortcode();
