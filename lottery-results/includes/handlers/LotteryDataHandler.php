<?php
/**
 * Classe responsável por validação e renderização de dados da loteria.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\handlers;

use LotteryResults\includes\helpers\CurrencyHelper;
use LotteryResults\Includes\Helpers\DateHelper;

/**
 * Classe base para validação e renderização de dados da loteria.
 */
abstract class LotteryDataHandler {

	/**
	 * Nome da loteria.
	 *
	 * @var string
	 */
	public string $lottery;

	/**
	 * Construtor.
	 *
	 * @param array $data Dados dos resultados da loteria.
	 */
	public function __construct( protected array $data ) {
		if ( empty( $this->data ) ) {
			// O método __construct() não deve retornar valores.
			throw new \InvalidArgumentException( esc_html__( 'Invalid data format for header.', 'lottery-results' ) );
		}

		$this->lottery = $this->data['loteria'];
	}

	/**
	 * Prepara os dados do cabeçalho, validando e formatando.
	 *
	 * @return array|WP_Error Array com os dados preparados ou WP_Error em caso de erro.
	 */
	protected function prepare_header_data() {
		$contest_date = esc_html( $this->data['data'] );
		$contest      = esc_html( $this->data['concurso'] );
		$day_of_week  = DateHelper::get_day_of_week( $this->data['data'] );
		$title        = "Concurso {$contest} • {$day_of_week} {$contest_date}";

		return array( "bg-{$this->data['loteria']}", $title );
	}

	/**
	 * Renderiza o HTML do cabeçalho.
	 *
	 * @return string HTML do cabeçalho.
	 */
	public function render_header() {
		[$bg_color_class, $title] = $this->prepare_header_data();

		ob_start();
		include plugin_dir_path( __FILE__ ) . '../../templates/lottery-results-header.php';
		return ob_get_clean();
	}

	/**
	 * Prepara os dados do corpo, validando e formatando.
	 *
	 * @return array|WP_Error Array com os dados preparados ou WP_Error em caso de erro.
	 */
	protected function prepare_body_data() {
		$drawn_numbers          = $this->prepare_drawn_numbers();
		$header_prizes          = $this->get_header_prizes();
		[$total_prize, $prizes] = $this->prepare_prizes();

		return array( $drawn_numbers, $total_prize, $prizes, $header_prizes );
	}

	/**
	 * Prepara os números sorteados.
	 *
	 * @return string Números sorteados formatados em HTML.
	 */
	protected function prepare_drawn_numbers() {
		$draw_numbers_content = '';

		foreach ( $this->data['dezenas'] as $number ) {
			$draw_numbers_content .= "<div class='lottery-drawn-number text-white bg-"
				. esc_attr( $this->lottery ) . "'>"
				. esc_html( $number ) . '</div>';
		}

		return $draw_numbers_content;
	}

	/**
	 * Gera o cabeçalho da tabela de prêmios.
	 *
	 * @return string Cabeçalho da tabela de prêmios em HTML.
	 */
	protected function get_header_prizes() {
		return "<tr class='text-" . $this->lottery . "'>"
			. '<th>Faixas</th>'
			. '<th>Ganhadores</th>'
			. '<th>Prêmio</th>'
			. '</tr>';
	}

	/**
	 * Prepara os prêmios.
	 *
	 * @return array Retorna o total do prêmio e o conteúdo dos prêmios.
	 */
	protected function prepare_prizes() {
		$prizes_content = '';
		$total_prize    = 0;

		foreach ( $this->data['premiacoes'] as $matches ) {
			$total_winners = (int) $matches['ganhadores'];

			if ( 0 === $total_winners ) {
				continue;
			}

			$total_prize += ( $matches['valorPremio'] * $total_winners );

			$prizes_content .= '<tr>'
				. '<td>' . esc_html( $matches['descricao'] ) . '</td>'
				. '<td>' . esc_html( $matches['ganhadores'] ) . '</td>'
				. '<td>' . CurrencyHelper::format_currency_brl( esc_html( $matches['valorPremio'] ) ) . '</td>'
				. '</tr>';
		}

		$total_prize = CurrencyHelper::format_currency_brl( $total_prize );

		return array( $total_prize, $prizes_content );
	}

	/**
	 * Renderiza o HTML do corpo.
	 *
	 * @return string HTML do corpo.
	 */
	public function render_body() {
		[$drawn_numbers, $total_prize, $prizes, $header_prizes] = $this->prepare_body_data();
		$aditional_class                                        = 'lottery-numbers-' . $this->lottery;

		ob_start();
		include plugin_dir_path( __FILE__ ) . '../../templates/lottery-results-body.php';
		return ob_get_clean();
	}
}
