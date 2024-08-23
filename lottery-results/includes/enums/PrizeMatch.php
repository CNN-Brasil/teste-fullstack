<?php
/**
 * Enumeração para correspondência de prêmios da loteria.
 *
 * Este arquivo define os diferentes tipos de correspondências de prêmios
 * disponíveis nas loterias e suas respectivas descrições e ordens.
 *
 * @package LotteryResults
 */

namespace LotteryResults\includes\enums;

/**
 * Enumeração para correspondência de prêmios da loteria.
 */
enum PrizeMatch: string {

	case SENA     = '6 acertos';
	case QUINA    = '5 acertos';
	case QUADRA   = '4 acertos';
	case TERNO    = '3 acertos';
	case DUPLA    = '2 acertos';
	case PRIMEIRO = '1 acertos';

	/**
	 * Converte uma string para a correspondência de prêmios.
	 *
	 * @param string $match_string O número de acertos em formato string.
	 * @return ?PrizeMatch Retorna a correspondência de prêmios ou null se não encontrado.
	 */
	public static function from_string( string $match_string ): ?PrizeMatch {
		return match ( $match_string ) {
			'6 acertos' => self::SENA,
			'5 acertos' => self::QUINA,
			'4 acertos' => self::QUADRA,
			'3 acertos' => self::TERNO,
			'2 acertos' => self::DUPLA,
			'1 acertos'  => self::PRIMEIRO,
			default => null,
		};
	}

	/**
	 * Retorna a descrição do prêmio.
	 *
	 * @return string Descrição do prêmio.
	 */
	public function get_description(): string {
		return match ( $this ) {
			self::SENA     => 'Sena',
			self::QUINA    => 'Quina',
			self::QUADRA   => 'Quadra',
			self::TERNO    => 'Terno',
			self::DUPLA    => 'Dupla',
			self::PRIMEIRO => 'Primeiro',
		};
	}

	/**
	 * Retorna a ordem do prêmio.
	 *
	 * @return string Ordem do prêmio.
	 */
	public function get_order(): string {
		return match ( $this ) {
			self::SENA     => '6°',
			self::QUINA    => '5°',
			self::QUADRA   => '4°',
			self::TERNO    => '3°',
			self::DUPLA    => '2°',
			self::PRIMEIRO => '1°',
		};
	}
}
