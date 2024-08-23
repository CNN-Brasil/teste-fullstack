<?php
/**
 * Classe responsável por validação e renderização de dados da loteria Federal.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\handlers;

use LotteryResults\includes\enums\PrizeMatch;
use LotteryResults\includes\helpers\CurrencyHelper;

/**
 * Classe base para validação e renderização de dados da loteria Federal.
 */
class FederalDataHandler extends LotteryDataHandler {

	/**
	 * Prepara os números sorteados.
	 *
	 * @return string Números sorteados formatados em HTML.
	 */
	protected function prepare_drawn_numbers() {
		$draw_numbers_content = '';

		foreach ( $this->data['dezenas'] as $number ) {
			$draw_numbers_content .= "<div class='lottery-drawn-number lottery-drawn-ticket text-white bg-"
				. esc_attr( $this->lottery ) . "'>"
				. esc_html( $number ) . '</div>';
		}

		return $draw_numbers_content;
	}

	/**
	 * Prepara os prêmios e calcula o total de prêmios distribuídos.
	 *
	 * @return array Retorna o total do prêmio e o conteúdo dos prêmios.
	 */
	protected function prepare_prizes() {
		$prizes_content = '';
		$total_prize    = 0;
		foreach ( $this->data['premiacoes'] as $matches ) {
			$total_winners = (int) $matches['ganhadores'];

			// Yoda Condition aplicada.
			if ( 0 === $total_winners ) {
				continue;
			}

			$match_description = PrizeMatch::from_string( esc_html( $matches['descricao'] ) );
			$total_prize      += ( $matches['valorPremio'] * $total_winners );

			$prizes_content .= '<tr>'
				. '<td>' . $match_description->get_order() . '</td>'
				. '<td>' . esc_html( $matches['ganhadores'] ) . '</td>'
				. '<td>' . CurrencyHelper::format_currency_brl( esc_html( $matches['valorPremio'] ) ) . '</td>'
				. '</tr>';
		}

		$total_prize = CurrencyHelper::format_currency_brl( $total_prize );

		return array( $total_prize, $prizes_content );
	}
}
