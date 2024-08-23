<?php
/**
 * Funções auxiliares para o plugin Lottery Results.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\helpers;

/**
 * Classe que contém funções auxiliares relacionadas à formatação de moeda.
 */
class CurrencyHelper {

	/**
	 * Formata um valor float para o formato monetário do Real brasileiro (R$).
	 *
	 * @param float $value O valor a ser formatado.
	 * @return string O valor formatado no formato R$ 1.234,56.
	 */
	public static function format_currency_brl( float $value ): string {
		return 'R$ ' . number_format( $value, 2, ',', '.' );
	}
}
