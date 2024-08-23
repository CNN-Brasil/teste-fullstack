<?php
/**
 * Classe base para validação e renderização de dados da loteria Timemania.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\handlers;

/**
 * Classe base para validação e renderização de dados da loteria Timemania.
 */
class TimemaniaDataHandler extends LotteryDataHandler {

	/**
	 * Prepara os números sorteados e o time do coração.
	 *
	 * @return string Números sorteados e time do coração formatados em HTML.
	 */
	protected function prepare_drawn_numbers() {
		$draw_numbers_content = '';

		foreach ( $this->data['dezenas'] as $number ) {
			$draw_numbers_content .= "<div class='lottery-drawn-number text-white bg-"
				. esc_attr( $this->lottery ) . "'>"
				. esc_html( $number ) . '</div>';
		}

		$draw_numbers_content .= "<div class='lottery-drawn-team text-timemania'>Time do coração: "
			. esc_html( $this->data['timeCoracao'] ) . '</div>';

		return $draw_numbers_content;
	}
}
