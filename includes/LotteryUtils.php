<?php

namespace LotteryChallenge;

/**
 * Class LotteryUtils
 * @package LotteryChallenge
 * 
 * Funções utilitárias para manipulação de resultados de loterias
 */
class LotteryUtils
{
    /**
     * Formata a data no formato "dia da semana d/m/Y".
     * 
     * @param string $date Data a ser formatada
     * @return string Data formatada no formato "dia da semana d/m/Y" ou a data original se não for válida
     */
    public static function format_date_with_day($date)
    {
        $date_obj = \DateTime::createFromFormat('d/m/Y', $date);
        if (!$date_obj) {
            return $date;
        }

        $days_of_week = array(
            'Sunday'    => 'Domingo',
            'Monday'    => 'Segunda-feira',
            'Tuesday'   => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday'  => 'Quinta-feira',
            'Friday'    => 'Sexta-feira',
            'Saturday'  => 'Sábado',
        );

        $day_of_week = $days_of_week[$date_obj->format('l')];
        return $day_of_week . ' ' . $date_obj->format('d/m/Y');
    }

    /**
     * Mapeia as descrições dos prêmios (megasena) para nomes mais amigáveis.
     * 
     * @param string $description Descrição do prêmio
     * @return string Nome amigável do prêmio ou a descrição original se não for encontrada
     */
    public static function map_award_descriptions($description)
    {
        $awards = [
            '6 acertos' => 'Sena',
            '5 acertos' => 'Quina',
            '4 acertos' => 'Quadra'
        ];

        return $awards[$description] ?? $description;
    }
}