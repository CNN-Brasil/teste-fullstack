<?php
/**
 * CNN Loterias Shortcode Class
 *
 * This class handles the shortcode functionality for displaying lottery results.
 *
 * @package CNN_Brasil_Loterias
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CNN_Loterias_Shortcode Class.
 */
class CNN_Loterias_Shortcode {

	/**
	 * Initialize the Shortcode class.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_shortcode( 'loterias', array( __CLASS__, 'display' ) );
	}

	/**
	 * Display the lottery results.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output of the lottery results.
	 */
	public static function display( $atts ) {
		$atts = shortcode_atts(
			array(
				'loteria'  => 'megasena',
				'concurso' => 'ultimo',
				'debug'    => 'false',
			),
			$atts,
			'loterias'
		);

		$loteria  = sanitize_text_field( $atts['loteria'] );
		$concurso = sanitize_text_field( $atts['concurso'] );
		$debug    = filter_var( $atts['debug'], FILTER_VALIDATE_BOOLEAN );

		$result = CNN_Loterias_API::fetch_results( $loteria, $concurso );

		if ( is_wp_error( $result ) ) {
			return self::render_error( $result->get_error_message(), $debug );
		}

		$result['loteria_nome']   = self::get_loteria_name( $loteria );
		$result['data_formatada'] = isset( $result['data'] ) ? self::format_date_with_day( $result['data'] ) : 'Data não disponível';

		$result['debug_info'] = array(
			'loteria'      => $loteria,
			'concurso'     => $concurso,
			'api_response' => $result,
		);

		return self::render_template( $result, $loteria, $debug );
	}

	/**
	 * Render the template.
	 *
	 * @since 1.0.0
	 * @param array   $result  The lottery results.
	 * @param string  $loteria The lottery type.
	 * @param boolean $debug   Whether to display debug information.
	 * @return string The rendered HTML.
	 */
	private static function render_template( $result, $loteria, $debug ) {
		$color                = self::map_loteria_color( $loteria );
		$format_date_with_day = array( __CLASS__, 'format_date_with_day' );
		$map_descricao        = array( __CLASS__, 'map_descricao' );

		ob_start();
		include CNN_LOTERIAS_PLUGIN_DIR . 'templates/lottery-results.php';
		$output = ob_get_clean();

		if ( $debug && current_user_can( 'manage_options' ) ) {
			$output .= '<pre>' . esc_html( wp_json_encode( $result['debug_info'], JSON_PRETTY_PRINT ) ) . '</pre>';
		}

		return $output;
	}

	/**
	 * Render an error message.
	 *
	 * @since 1.0.0
	 * @param string  $message The error message.
	 * @param boolean $debug   Whether to display debug information.
	 * @return string The rendered HTML.
	 */
	private static function render_error( $message, $debug ) {
		if ( $debug && current_user_can( 'manage_options' ) ) {
			return '<p>Error: ' . esc_html( $message ) . '</p>';
		} else {
			return '<p>' . esc_html__( 'Unable to fetch lottery results. Please try again later.', 'cnn-brasil-loterias' ) . '</p>';
		}
	}

	/**
	 * Map the lottery type to its corresponding color.
	 *
	 * @since 1.0.0
	 * @param string $loteria The lottery type.
	 * @return string The color code.
	 */
	public static function map_loteria_color( $loteria ) {
		$color_map = array(
			'megasena'   => '#2D976A',
			'lotofacil'  => '#921788',
			'quina'      => '#261383',
			'lotomania'  => '#F58123',
			'timemania'  => '#3DAF3E',
			'duplasena'  => '#A41628',
			'federal'    => '#133497',
			'diadesorte' => '#CA8536',
			'supersete'  => '#A9CF50',
		);
		return isset( $color_map[ $loteria ] ) ? $color_map[ $loteria ] : '#000000';
	}

	/**
	 * Format the date with the day of the week.
	 *
	 * @since 1.0.0
	 * @param string $date The date in d/m/Y format.
	 * @return string The formatted date with the day of the week.
	 */
	public static function format_date_with_day( $date ) {
		$date_obj = DateTime::createFromFormat( 'd/m/Y', $date );
		if ( ! $date_obj ) {
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

		$day_of_week = $days_of_week[ $date_obj->format( 'l' ) ];
		return $day_of_week . ', ' . $date_obj->format( 'd/m/Y' );
	}

	/**
	 * Map the description to a more user-friendly format.
	 *
	 * @since 1.0.0
	 * @param string $descricao The original description.
	 * @return string The mapped description.
	 */
	public static function map_descricao( $descricao ) {
		$map = array(
			'6 acertos' => 'Sena',
			'5 acertos' => 'Quina',
			'4 acertos' => 'Quadra',
		);
		return isset( $map[ $descricao ] ) ? $map[ $descricao ] : $descricao;
	}

	/**
	 * Get the full name of the lottery.
	 *
	 * @since 1.0.0
	 * @param string $loteria The lottery type.
	 * @return string The full name of the lottery.
	 */
	public static function get_loteria_name( $loteria ) {
		$names = array(
			'megasena'   => 'Mega-Sena',
			'lotofacil'  => 'Lotofácil',
			'quina'      => 'Quina',
			'lotomania'  => 'Lotomania',
			'timemania'  => 'Timemania',
			'duplasena'  => 'Dupla Sena',
			'federal'    => 'Loteria Federal',
			'diadesorte' => 'Dia de Sorte',
			'supersete'  => 'Super Sete',
		);
		return isset( $names[ $loteria ] ) ? $names[ $loteria ] : ucfirst( $loteria );
	}
}
