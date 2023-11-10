<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Você não tem permissão para acessar esta página diretamente.' );
}
if (
	is_array( spl_autoload_functions() )
	&& in_array( '__autoload', spl_autoload_functions() )
) {
	spl_autoload_register( '__autoload' );
}
if ( ! function_exists( 'cnn_autoload' ) ) {
	function cnn_autoload( $dir ): void {
		if ( ! file_exists( "$dir/composer.json" ) ) {
			return;
		}
		$composer   = json_decode( file_get_contents( "$dir/composer.json" ), 1 );
		$namespaces = $composer['autoload']['psr-4'] ?? [];
		foreach ( $namespaces as $namespace => $classpaths ) {
			if ( ! is_array( $classpaths ) ) {
				$classpaths = array( $classpaths );
			}
			spl_autoload_register( function( $classname ) use ( $namespace, $classpaths, $dir ) {
				if ( preg_match( "#^" . preg_quote( $namespace ) . "#", $classname ) ) {
					$classname = str_replace( $namespace, "", $classname );
					$filename  = preg_replace( "#\\\\#", "/", $classname ) . ".php";
					foreach ( $classpaths as $classpath ) {
						$full_path = $dir . "/" . $classpath . "/$filename";
						if ( file_exists( $full_path ) ) {
							include_once $full_path;
						}
					}
				}
			} );
		}
	}
}
