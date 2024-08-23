<?php
/**
 * Classe responsável por validação e renderização de dados da loteria Dia de Sorte.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\handlers;

/**
 * Classe base para validação e renderização de dados da loteria Dia de Sorte.
 */
class DiadesorteDataHandler extends LotteryDataHandler {

	/**
	 * Prepara os números sorteados, incluindo o mês da sorte.
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

		$draw_numbers_content .= "<div class='lottery-drawn-team text-diadesorte'>Mês da sorte: "
			. esc_html( $this->data['mesSorte'] ) . '</div>';

		return $draw_numbers_content;
	}
}
