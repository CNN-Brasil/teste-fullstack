<?php
/**
 * CNN Loterias Shortcode Class
 *
 * This class handles the shortcode functionality for displaying lottery results.
 *
 * @package CNN_Brasil_Loterias
 */

/**
 * Class CNN_Loterias_Shortcode
 */
class CNN_Loterias_Shortcode {
	/**
	 * Register the shortcode.
	 */
	public static function register() {
		add_shortcode( 'loterias', array( __CLASS__, 'display' ) );
	}

	/**
	 * Display the lottery results.
	 *
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
			$error_message = $result->get_error_message();
			$error_data    = $result->get_error_data();

			if ( $debug && current_user_can( 'manage_options' ) ) {
				return '<p>Error fetching lottery results: ' . esc_html( $error_message ) . '</p>'
					. '<pre>' . esc_html( wp_json_encode( $error_data, JSON_PRETTY_PRINT ) ) . '</pre>';
			} else {
				return '<p>Unable to fetch lottery results. Please try again later.</p>';
			}
		}

		$color         = self::map_loteria_color( $loteria );
		$date_with_day = self::format_date_with_day( $result['data'] );

		ob_start();
		?>
		<style>
			.cnn-loteria-result .header,
			.cnn-loteria-result .dezena {
				background-color: <?php echo esc_html( $color ); ?>;
			}
		</style>
		<div class="cnn-loteria-result">
			<div class="header">
				<h2>Concurso <?php echo esc_html( $result['concurso'] ); ?> • <?php echo esc_html( $date_with_day ); ?></h2>
			</div>
			<?php if ( isset( $result['dezenas'] ) && is_array( $result['dezenas'] ) ) : ?>
				<div class="dezenas">
					<?php foreach ( $result['dezenas'] as $dezena ) : ?>
						<div class="dezena"><?php echo esc_html( $dezena ); ?></div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p>Números sorteados não disponíveis.</p>
			<?php endif; ?>
			<hr class="divider">
			<?php if ( empty( $result['premiacoes'] ) ) : ?>
				<p><em>Informações de premiação não disponíveis para este concurso.</em></p>
			<?php else : ?>
				<div class="premio-area">
					<p>PRÊMIO</p>
					<h3>R$ <?php echo esc_html( number_format( $result['premiacoes'][0]['valorPremio'] ?? 0, 2, ',', '.' ) ); ?></h3>
					<hr class="divider">
				</div>
				<div class="premiacoes-titles">
					<span class="descricao">Faixas</span>
					<span class="ganhadores">Ganhadores</span>
					<span class="valor-premio">Prêmio</span>
				</div>
				<hr class="divider">
				<div class="premiacoes">
					<?php foreach ( $result['premiacoes'] as $premio ) : ?>
						<div class="premiacao-row">
							<span class="descricao"><?php echo esc_html( self::map_descricao( $premio['descricao'] ?? '' ) ); ?></span>
							<span class="ganhadores"><?php echo esc_html( $premio['ganhadores'] ?? 0 ); ?></span>
							<span class="valor-premio">R$ <?php echo esc_html( number_format( $premio['valorPremio'] ?? 0, 2, ',', '.' ) ); ?></span>
						</div>
						<hr class="divider">
					<?php endforeach; ?>
				</div>
				<?php if ( isset( $result['acumulou'] ) ) : ?>
					<p><?php echo $result['acumulou'] ? 'Acumulou!' : 'Não acumulou.'; ?></p>
				<?php endif; ?>
				<?php if ( isset( $result['valorEstimadoProximoConcurso'] ) ) : ?>
					<p>Estimativa de prêmio do próximo concurso: R$ <?php echo esc_html( number_format( $result['valorEstimadoProximoConcurso'], 2, ',', '.' ) ); ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php if ( $debug && current_user_can( 'manage_options' ) ) : ?>
			<pre><?php echo esc_html( wp_json_encode( $result, JSON_PRETTY_PRINT ) ); ?></pre>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Map the description to a more user-friendly format.
	 *
	 * @param string $descricao The original description.
	 * @return string The mapped description.
	 */
	private static function map_descricao( $descricao ) {
		$map = array(
			'6 acertos' => 'Sena',
			'5 acertos' => 'Quina',
			'4 acertos' => 'Quadra',
		);
		return isset( $map[ $descricao ] ) ? $map[ $descricao ] : $descricao;
	}

	/**
	 * Map the lottery type to its corresponding color.
	 *
	 * @param string $loteria The lottery type.
	 * @return string The color code.
	 */
	private static function map_loteria_color( $loteria ) {
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
	 * @param string $date The date in d/m/Y format.
	 * @return string The formatted date with the day of the week.
	 */
	private static function format_date_with_day( $date ) {
		$date_obj = DateTime::createFromFormat( 'd/m/Y', $date );
		if ( ! $date_obj ) {
			return $date;
		}

		$days_of_week = array(
			'Sunday'    => 'Domingo',
			'Monday'    => 'Segunda-Feira',
			'Tuesday'   => 'Terça-Feira',
			'Wednesday' => 'Quarta-Feira',
			'Thursday'  => 'Quinta-Feira',
			'Friday'    => 'Sexta-Feira',
			'Saturday'  => 'Sábado',
		);

		$day_of_week = $days_of_week[ $date_obj->format( 'l' ) ];
		return $day_of_week . ' ' . $date_obj->format( 'd/m/Y' );
	}
}