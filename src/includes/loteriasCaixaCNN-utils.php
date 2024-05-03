<?php
/**
 * Classe utilitários.
 * @package LoteriasCaixaCNN
 */
class loteriasCaixaCNN_Utils {

	/**
	 * Obtem o dia da semana por meio de uma data
	 * @param string $date no formato 'DD/MM/YYYY'.
	 * @return string Dia da semana.
	 */
	public static function get_week_day(string $date) : string {
		// converte a data para o formato americano
		$data = implode('-', array_reverse(explode('/', $date)));

		// Obtém o timestamp da data
		$timestamp = strtotime($data);

		// Obtém o nome completo do dia da semana em inglês
		$dia_da_semana = gmdate('l', $timestamp);
		
		// Traduz o dia da semana para o português
		switch ($dia_da_semana) {
			case 'Monday':
				return 'Segunda-feira';
			case 'Tuesday':
				return 'Terça-feira';
			case 'Wednesday':
				return 'Quarta-feira';
			case 'Thursday':
				return 'Quinta-feira';
			case 'Friday':
				return 'Sexta-feira';
			case 'Saturday':
				return 'Sábado';
			case 'Sunday':
				return 'Domingo';
			default:
				return 'Dia desconhecido';
		}
	}

	/**
	 * Formata um número como uma string com ou sem casa decimais.
	 *
	 * @param float $number Número a ser formatado.
	 * @param bool  $delete_decimal verifica se os decimais devem ser removidos.
	 * @return string Número formatado.
	 */
	public static function format_currency( $number, $delete_decimal = false ) : string {
		return number_format( $number, $delete_decimal ? 0 : 2, ',', '.' );
	}
}