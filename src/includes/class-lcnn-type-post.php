<?php
/**
 * Classe para custom type post.
 *
 * @package LCNN
 */
class LCNN_Post_Type {


	/**
	 * Construtor adiciona a inicialização para o rehgistro.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Registra o tipo de post personalizado 'loterias'.
	 */
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
	}//end register_post_type()
}//end class
