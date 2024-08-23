<div class="lottery-body">
	<div class="lottery-numbers lottery-body-section <?php echo $aditional_class; ?>">
		<?php echo $drawn_numbers; ?>
	</div>

	<div class="lottery-prize lottery-body-section">
		<p><?php esc_html_e( 'PRÊMIO', 'lottery-results' ); ?></p>
		<p><?php echo $total_prize; ?></p>
	</div>

	<div class="lottery-winners lottery-body-section">
		<table>
			<thead>
				<?php echo $header_prizes; ?>
			</thead>
			<tbody>
				<?php echo $prizes; ?>
			</tbody>
		</table>
	</div>

</div>
