<?php

if ( is_wp_error( $data ) ) {
	return $data->get_error_message();
}

$lotteryCamelCase = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $lottery ) ) );
$handlerClass     = "LotteryResults\\Includes\\Handlers\\{$lotteryCamelCase}DataHandler";

if ( class_exists( $handlerClass ) ) {
	$handler = new $handlerClass( $data );
}

?>

<div class="lottery-container shadow-box">
	<?php
	echo $handler->render_header();
	echo $handler->render_body();
	?>
</div>
