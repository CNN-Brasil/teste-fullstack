<?php
/**
 * Classe custom type post.
 * @package LoteriasCaixaCNN
 */
class loteriasCaixaCNN_Post_Type {

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	public function register_post_type(): void {
		$args = array(
			'public'      => true,
			'label'       => 'Loterias',
			'supports'    => array(
				'title',
				'editor',
			),
			'has_archive' => true,
		);
		register_post_type( 'loterias', $args );
	}
}