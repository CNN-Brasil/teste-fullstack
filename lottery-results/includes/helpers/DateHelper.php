<?php
/**
 * Funções auxiliares para o plugin Lottery Results.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\helpers;

/**
 * Classe que contém funções auxiliares relacionadas a datas.
 */
class DateHelper {

	/**
	 * Obtém o dia da semana a partir de uma string de data.
	 *
	 * @param string $date_str String de data no formato 'dd/mm/yyyy'.
	 * @return string Dia da semana em português.
	 */
	public static function get_day_of_week( string $date_str ): string {
		$timestamp           = strtotime( str_replace( '/', '-', $date_str ) );
		$day_of_week_english = date( 'l', $timestamp );

		$days_of_week = array(
			'Sunday'    => 'Domingo',
			'Monday'    => 'Segunda-feira',
			'Tuesday'   => 'Terça-feira',
			'Wednesday' => 'Quarta-feira',
			'Thursday'  => 'Quinta-feira',
			'Friday'    => 'Sexta-feira',
			'Saturday'  => 'Sábado',
		);

		return $days_of_week[ $day_of_week_english ];
	}
}
