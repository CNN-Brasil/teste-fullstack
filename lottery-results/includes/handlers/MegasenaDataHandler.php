<?php
/**
 * Classe base para validação e renderização de dados da loteria Megasena.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\handlers;

use LotteryResults\includes\enums\PrizeMatch;
use LotteryResults\includes\helpers\CurrencyHelper;

/**
 * Classe base para validação e renderização de dados da loteria Megasena.
 */
class MegasenaDataHandler extends LotteryDataHandler {

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

			if ( 0 === $total_winners ) {
				continue;
			}

			$match_description = PrizeMatch::from_String( esc_html( $matches['descricao'] ) );
			$total_prize      += ( $matches['valorPremio'] * $total_winners );

			$prizes_content .= '<tr>'
				. '<td>' . $match_description->get_description() . '</td>'
				. '<td>' . esc_html( $matches['ganhadores'] ) . '</td>'
				. '<td>' . CurrencyHelper::format_currency_brl( esc_html( $matches['valorPremio'] ) ) . '</td>'
				. '</tr>';
		}

		$total_prize = CurrencyHelper::format_currency_brl( $total_prize );

		return array( $total_prize, $prizes_content );
	}
}
