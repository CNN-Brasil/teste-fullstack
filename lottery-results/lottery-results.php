<?php
/*
Plugin Name: Loterias da Caixa
Plugin URI:  https://seusite.com/lottery-results
Description: Mostra os resultados das loterias da Caixa Econômica Federal.
Version:     1.0.0
Author:      Seu Nome
Author URI:  https://seusite.com
License:     GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	function ( $class_name ) {
		$prefix = 'LotteryResults\\';
		$len    = strlen( $prefix );

		if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class_name, $len );
		$file           = plugin_dir_path( __FILE__ ) . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

new \LotteryResults\includes\LotteryResults();
