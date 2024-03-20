<?php
/**
 * Classe própria de utilitários.
 *
 * @package LCNN
 */
class LCNN_Utils {

	/**
	 * Retorna o dia da semana de uma data fornecida.
	 *
	 * @param string $date Data no formato 'd/m/Y'.
	 * @return string Dia da semana correspondente à data.
	 */
	public static function get_week_day(string $date ) : string {
		$date    = DateTime::createFromFormat( 'd/m/Y', $date );
		return $date->format( 'l' );
	}

	/**
	 * Formata um número como uma string com ou sem casa decimais.
	 *
	 * @param float $number Número a ser formatado.
	 * @param bool  $delete_decimal Define se os decimais devem ser removidos.
	 * @return string Número formatado.
	 */
	public static function format_currency( $number, $delete_decimal = false ) : string {
		return number_format( $number, $delete_decimal ? 0 : 2, ',', '.' );
	}
}
