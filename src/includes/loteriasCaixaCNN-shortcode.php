<?php
/**
 * Classe para gerenciar shortcodes.
 * @package LoteriasCaixaCNN
 */
class loteriasCaixaCNN_Shortcode extends lc_cnn {

	/**
	 * Cria um shortcode e seu comportamento.
	 * @param array $atts Atributos passados para o shortcode.
	 * @return string O conteúdo renderizado do shortcode.
	 */
	public function create_shortcode(array $atts ) : string {

		$atts = shortcode_atts( $this->params, $atts );

		if ( empty( $atts['loteria'] ) || !in_array($atts['loteria'], $this->loterias) ) :
			return __( 'Error: Forneça um parametro válido para o campo "loteria"', 'loteria' );
		endif;

		$this->set_loteria( sanitize_text_field( $atts['loteria'] ) );

		// Se estiver vazio, utiliza o lastest já iniciado no constructor da base.
		if ( ! empty( $atts['concurso'] ) ) {
			$this->set_concurso( sanitize_text_field( $atts['concurso'] ) );
		}

		$lccnn_api = new loteriasCaixaCNN_API();

		$result = $lccnn_api->get_data( $this->params['loteria'], $this->params['concurso'] );

		if(count($result) > 0){
			$this->set_array_loteria( $result );
			$this->render_shortcode( $render );

			return $render;
		}
		
		return __( 'Error: Forneça um parametro válido para o campo "loteria"', 'loteria' );
	}

    /**
     * Renderizndo o conteúdo.
     * @param string|null.
     */
	private function render_shortcode(?string &$render ) : void {

		extract( $this->loteria );

		ob_start();
		include plugin_dir_path( __FILE__ ) . '../view/shortcode.php';
		$output = ob_get_contents();
		ob_end_clean();

		$render = $output;
	}
}