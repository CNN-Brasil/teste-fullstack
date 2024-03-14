<?php
namespace Cnnbr\TesteFullstack\includes;

	/**
	 * Registra o Custom Post Type para as loterias.
	 *
	 * @package LoteriasCaixa
	 */

	/**
	 * Classe para registrar o CPT de loterias.
	 */

class Loterias_CPT {

		/**
		 * Construtor.
		 */
	public function __construct() {
		add_action( 'init', array( $this, 'registrar_cpt_loterias' ) );
	}

	/**
	 * Registra o Custom Post Type "Loterias".
	 */
	public static function registrar_cpt_loterias() {
		$args = array(
			'public'      => true,
			'label'       => 'Loterias',
			'supports'    => array( 'title', 'editor' ),
			'has_archive' => true,
		);
		register_post_type( 'loterias', $args );
	}
}

new Loterias_CPT();
