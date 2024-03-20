<?php
/**
 * Classe própria para gerenciar shortcodes.
 *
 * @package LCNN
 */
class LCNN_Shortcode extends LCNN_Base {

	/**
	 * Cria um shortcode e define seu comportamento.
	 *
	 * @param array $atts Atributos passados para o shortcode.
     *
	 * @return string O conteúdo renderizado do shortcode.
	 */
	public function create_shortcode(array $atts ) : string {

		$atts = shortcode_atts( $this->params, $atts );

		if ( empty( $atts['loteria'] ) || !in_array($atts['loteria'], $this->loterias) ) :
			return __( 'Error: Fornecer parametro válido para o campo "loteria"', 'loteria' );
		endif;

		$this->set_loteria( sanitize_text_field( $atts['loteria'] ) );

		// Se estiver vazio, utiliza o lastest já iniciado na constructor da base.
		if ( ! empty( $atts['concurso'] ) ) {
			$this->set_concurso( sanitize_text_field( $atts['concurso'] ) );
		}

		$lcnn_api = new LCNN_API();

		$result = $lcnn_api->get_data( $this->params['loteria'], $this->params['concurso'] );

		$this->set_array_loteria( $result );

		$this->render_shortcode( $render );

		return $render;
	}//end create_shortcode()


    /**
     * Renderizndo o conteúdo para o shortcode.
     *
     * @param string|null $render Referêncial para armazenar o conteúdo renderizado a ser usado na classe principal.
     */
	private function render_shortcode(?string &$render ) : void {

		extract( $this->loteria );

		ob_start();
		include plugin_dir_path( __FILE__ ) . '../template/template-lcnn-shortcode.php';
		$output = ob_get_contents();
		ob_end_clean();

		$render = $output;
	}
}//end class
