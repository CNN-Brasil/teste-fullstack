<?php
/**
 * Template for displaying lottery results.
 *
 * @package CNN_Brasil_Loterias
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="cnn-loteria-result" style="--loteria-color: <?php echo esc_attr( $color ); ?>">
	<div class="header">
		<h2>
			<?php
			$header_text = sprintf(
				'%s - Concurso %s',
				$result['loteria_nome'] ?? 'Loteria',
				$result['concurso'] ?? 'N/A'
			);
			echo esc_html( $header_text );
			?>
			• <?php echo esc_html( call_user_func( $format_date_with_day, $result['data'] ) ); ?>
		</h2>
	</div>
	<?php if ( isset( $result['dezenas'] ) && is_array( $result['dezenas'] ) && ! empty( $result['dezenas'] ) ) : ?>
		<div class="dezenas">
			<?php foreach ( $result['dezenas'] as $dezena ) : ?>
				<div class="dezena"><?php echo esc_html( $dezena ); ?></div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'Números sorteados não disponíveis.', 'cnn-brasil-loterias' ); ?></p>
	<?php endif; ?>
	<hr class="divider">
	<?php if ( empty( $result['premiacoes'] ) ) : ?>
		<p><em><?php esc_html_e( 'Informações de premiação não disponíveis para este concurso.', 'cnn-brasil-loterias' ); ?></em></p>
	<?php else : ?>
		<div class="premio-area">
			<p><?php esc_html_e( 'PRÊMIO', 'cnn-brasil-loterias' ); ?></p>
			<h3><?php echo esc_html( 'R$ ' . number_format( $result['premiacoes'][0]['valorPremio'] ?? 0, 2, ',', '.' ) ); ?></h3>
		</div>
		<hr class="divider">
		<div class="premiacoes-titles">
			<span class="descricao"><?php esc_html_e( 'Faixas', 'cnn-brasil-loterias' ); ?></span>
			<span class="ganhadores"><?php esc_html_e( 'Ganhadores', 'cnn-brasil-loterias' ); ?></span>
			<span class="valor-premio"><?php esc_html_e( 'Prêmio', 'cnn-brasil-loterias' ); ?></span>
		</div>
		<hr class="divider">
		<div class="premiacoes">
			<?php foreach ( $result['premiacoes'] as $premio ) : ?>
				<div class="premiacao-row">
					<span class="descricao"><?php echo esc_html( call_user_func( $map_descricao, $premio['descricao'] ?? '' ) ); ?></span>
					<span class="ganhadores"><?php echo esc_html( $premio['ganhadores'] ?? 0 ); ?></span>
					<span class="valor-premio"><?php echo esc_html( 'R$ ' . number_format( $premio['valorPremio'] ?? 0, 2, ',', '.' ) ); ?></span>
				</div>
				<hr class="divider">
			<?php endforeach; ?>
		</div>
		<?php if ( isset( $result['acumulou'] ) ) : ?>
			<p><?php echo $result['acumulou'] ? esc_html__( 'Acumulou!', 'cnn-brasil-loterias' ) : esc_html__( 'Não acumulou.', 'cnn-brasil-loterias' ); ?></p>
		<?php endif; ?>
		<?php if ( isset( $result['valorEstimadoProximoConcurso'] ) ) : ?>
			<?php /* translators: %s: is the estimated prize value for the next lottery draw */ ?>
            <p><?php echo esc_html( sprintf( __( 'Estimativa de prêmio do próximo concurso: R$ %s', 'cnn-brasil-loterias' ), number_format( $result['valorEstimadoProximoConcurso'], 2, ',', '.' ) ) ); ?></p>
        <?php endif; ?>
	<?php endif; ?>
</div>
