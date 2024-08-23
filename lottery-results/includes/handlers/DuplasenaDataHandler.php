<?php
/**
 * Classe responsável por validação e renderização de dados da loteria Dupla Sena.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\handlers;

use LotteryResults\includes\helpers\CurrencyHelper;

/**
 * Classe base para validação e renderização de dados da loteria Dupla Sena.
 */
class DuplasenaDataHandler extends LotteryDataHandler {

	/**
	 * Prepara os números sorteados para os dois sorteios.
	 *
	 * @return string Números sorteados formatados em HTML.
	 */
	protected function prepare_drawn_numbers() {
		$draw_numbers_content = '';

		foreach ( $this->data['dezenas'] as $index => $number ) {

			switch ( $index ) {
				case 0:
					$draw_numbers_content .= "<div class='lottery-drawn-title text-white text-duplasena'>"
						. esc_html__( '1º sorteio', 'lottery-results' ) . '</div>';
					break;

				case 6:
					$draw_numbers_content .= "<div class='lottery-drawn-title text-white text-duplasena'>"
						. esc_html__( '2º sorteio', 'lottery-results' ) . '</div>';
					break;

				default:
					// Caso não haja ação a ser realizada no switch.
			}

			$draw_numbers_content .= "<div class='lottery-drawn-number text-white bg-duplasena'>"
				. esc_html( $number ) . '</div>';
		}

		return $draw_numbers_content;
	}

	/**
	 * Prepara os prêmios, incluindo a distinção entre o 1º e o 2º sorteios.
	 *
	 * @return array Retorna o total do prêmio e o conteúdo dos prêmios.
	 */
	protected function prepare_prizes() {
		$prizes_content = '';
		$total_prize    = 0;
		foreach ( $this->data['premiacoes'] as $index => $matches ) {
			$draw_index    = $index < 5 ? ' (1°)' : ' (2°)';
			$total_winners = (int) $matches['ganhadores'];

			// Yoda Condition para evitar erros de atribuição acidental.
			if ( 0 === $total_winners ) {
				continue;
			}

			$total_prize += ( $matches['valorPremio'] * $total_winners );

			$prizes_content .= '<tr>'
				. '<td>' . esc_html( $matches['descricao'] . $draw_index ) . '</td>'
				. '<td>' . esc_html( $matches['ganhadores'] ) . '</td>'
				. '<td>' . CurrencyHelper::format_currency_brl( esc_html( $matches['valorPremio'] ) ) . '</td>'
				. '</tr>';
		}

		$total_prize = CurrencyHelper::format_currency_brl( $total_prize );

		return array( $total_prize, $prizes_content );
	}
}
