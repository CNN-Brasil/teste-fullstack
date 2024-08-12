<?php
/**
 * Plugin Name: Plugin de Loterias
 * Plugin URI: https://planet1.com.br
 * Description: Realizando o teste para a vaga de programador.
 * Version: 1.0
 * Author: Horácio
 * Author URI: https://planet1.com.br
 * License: GPLv2 or later
 *
 * @package loterias
 */

/** Esta função vai carregar o css do plugin. */
function meu_plugin_enqueue_styles() {
	wp_enqueue_style( 'meu-plugin-estilo', plugin_dir_url( __FILE__ ) . 'css/estilo.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'meu_plugin_enqueue_styles' );


require_once 'autoload.php';
use loterias\classes\Loteria;


defined( 'ABSPATH' ) || die( 'No script access allowed.' );

/**  Criar o post type "Loterias" ao ativar o plugin. */
function lm_register_post_type() {
	register_post_type(
		'loterias',
		array(
			'labels'      => array(
				'name'          => __( 'Loterias' ),
				'singular_name' => __( 'Loteria' ),
			),
			'public'      => true,
			'has_archive' => true,
			'supports'    => array( 'title', 'editor' ),
		)
	);
}
add_action( 'init', 'lm_register_post_type' );






/**
 * Descrição da função.
 *
 * @param string $atts nessa variável eu envio os dados de loteria e concurso.
 */
function lm_lottery_results_shortcode( $atts ) {
		$x    = new Loteria();
		$atts = shortcode_atts(
			array(
				'concurso' => 'ultimo',
				'loteria'  => '0',
			),
			$atts,
			'lottery_results'
		);
	$concurso = sanitize_text_field( $atts['concurso'] );
	$loteria  = sanitize_text_field( $atts['loteria'] );
	$results  = '';

	if ( 'ultimo' !== $atts['concurso'] ) {
		$x->concurso = $atts['concurso'];
	}

	if ( 0 !== $atts['loteria'] ) {
		$x->loteria = $atts['loteria'];
	} else {
		echo 'loteria não informada';
	}
	$dados = $x->acessa_api()->dados;
	echo wp_kses_post( $dados );
}
add_shortcode( 'lottery_results', 'lm_lottery_results_shortcode' );
