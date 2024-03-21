<?php
if (!defined('ABSPATH')) {
    exit();
}

class LoteriasCaixa{

    public function __construct() { 
		add_action('wp_enqueue_scripts', array($this, 'lotcaixa_front_scripts'));
        add_shortcode( 'loterias', array( $this, 'lotcaixa_render_shortcode' ) );
        add_action('admin_menu', array($this, 'lotcaixa_admin_menu'), 9999);
        add_action('admin_enqueue_scripts', array($this, 'lotcaixa_admin_scripts'));		
	}

    // Add Pages admin
    public function lotcaixa_admin_menu() {  
		add_submenu_page( 'edit.php?post_type=loterias', __('Documentação', 'loterias-caixa'), __('Documentação', 'loterias-caixa'), 'manage_options', 'lotcaixa_documentation_page', array( $this, 'lotcaixa_documentation_page' ) );			
    }

	public function lotcaixa_documentation_page() {
		lotcaixa_documentation_page_content(); 
	}

    /*
    *
    * Registra os assets do plugin.
    *     
    */
    public function lotcaixa_front_scripts() {			
        // Registrar CSS
        wp_register_style( 'lotcaixa-root-style', esc_url(LOTCAIXA_URL).'src/assets/css/root.css', array(), esc_html(LOTCAIXA_VERSION) );	
        wp_register_style( 'lotcaixa-style', esc_url(LOTCAIXA_URL).'src/assets/css/lotcaixa.css', array(), esc_html(LOTCAIXA_VERSION) );				
        
        // Registrar JS		
        //wp_register_script( 'lotcaixa-script', esc_url(LOTCAIXA_URL).'src/assets/js/lotcaixa.js', array(), esc_html(LOTCAIXA_VERSION), true );		       
    }
    public function lotcaixa_admin_scripts() {
		global $pagenow;
		global $typenow;
		$currentScreen = get_current_screen();
		$current_page = $currentScreen->id;
		$pages = array(			
			'loterias_page_lotcaixa_documentation_page',            		
	    );

		if ( in_array( $current_page, $pages) || $typenow === 'loterias') {			
			wp_enqueue_style( 'lotcaixa-admin-style', esc_url(LOTCAIXA_URL).'src/assets/css/lotcaixa-admin.css', array(), esc_html(LOTCAIXA_VERSION) ); 
            wp_enqueue_script( 'lotcaixa-admin-script', esc_url(LOTCAIXA_URL).'src/assets/js/lotcaixa-admin.js', array(), esc_html(LOTCAIXA_VERSION), true ); 
		}						
	}
    /*
     * Renderiza o shortcode para exibir informações sobre uma loteria específica.
     *     
    */    
	public function lotcaixa_render_shortcode( $atts ) {
        // Attributes
        $atts = shortcode_atts(
            array(            
                'loteria' => '',
                'concurso' => 'ultimo'
            ), $atts
        ); 
    
        // Verificar os parâmetros
        $loteria = $atts['loteria'];
        $concurso = !empty($atts['concurso']) ? $atts['concurso'] : 'ultimo';
    
        // Tratar erros nos parâmetros
        $error_message = $this->lotcaixa_validate_parameters($loteria, $concurso);
        if ($error_message) {
            return '<p class="error">' . esc_html($error_message) . '</p>';
        }
    
        // Obter dados do concurso
        $result = $this->lotcaixa_get_concurso_data($loteria, $concurso);        
        if (!$result) {
            return '<p class="error">Não foi possível obter os resultados. Por favor, tente novamente mais tarde.</p>';
        }
        
        // Montar array com dados
        $args = array(
            "titulo" => $result['data']['head_title'],
            "loteria" => $loteria,
            "concurso" => $concurso === 'ultimo' ? $result['concurso_numero'] : $concurso,                
            "data_concurso" => $result['data']['data_concurso'],
            "valor_estimado" => $result['data']['valor_estimado'],
            "dezenas" => $result['data']['dezenas'],                
            "premiacoes" => $result['data']['premiacoes'],                
        );    
    
        // Criar novo CPT se necessário
        if (!$result['check_concurso']) {
            $this->lotcaixa_insert_new_loteria($args);
        }             
    
        // Retornar resultado
        return lotcaixa_view_loteria_resultado($args);
    }
    
    // Função para validar os parâmetros
    private function lotcaixa_validate_parameters($loteria, $concurso) {
        if (empty($loteria)) {
            return 'O parâmetro para loteria é obrigatório!';
        }
        if (!in_array($loteria, LOTERIAS_VALIDAS)) {
            return 'O parâmetro para loteria é inválido!';
        }
        if ($concurso != 'ultimo' && !preg_match('/^\d+$|^ultimo$/i', $concurso)) {
            return 'O parâmetro para concurso é inválido!';
        }
        return '';
    }
    
    // Função para obter os dados do concurso
    private function lotcaixa_get_concurso_data($loteria, $concurso) {
        if ($concurso === "ultimo") {

            $api = new LoteriasCaixa_API();
            $resultado_json = $api->get_concurso($loteria, $concurso);
            $resultado = json_decode($resultado_json, true); 
            if (!$resultado || isset($resultado['error'])) {
                return false;
            }
            $data_concurso = $resultado['data'];
            $day = lotcaixa_what_day_week($data_concurso);
            $head_title = 'Concurso '.$resultado['concurso'].' • '.$day. ' '.$data_concurso;
            $concurso_numero = $resultado['concurso'];            
            $dezenas = $resultado['dezenas'];
            $valor = $resultado['valorEstimadoProximoConcurso'];
            $valor_estimado = number_format($valor, 2, ',', '.');
            $premiacoes = $resultado['premiacoes'];
            // verificar se cpt existe
            $check_concurso = lotcaixa_check_loteria_by_concurso($resultado['concurso']);

        } else {
            // verificar se cpt existe
            $check_concurso = lotcaixa_check_loteria_by_concurso($concurso);
            if ($check_concurso) {

                $data_concurso = get_post_meta($check_concurso, 'data_concurso', true);
                $day = lotcaixa_what_day_week($data_concurso);
                $head_title = get_the_title($check_concurso);
                $concurso_numero = $concurso;
                $dezenas = get_post_meta($check_concurso, 'dezenas', true);                
                $valor_estimado = get_post_meta($check_concurso, 'valor_estimado', true);
                $premiacoes = get_post_meta($check_concurso, 'premiacoes', true);

            } else {
                $api = new LoteriasCaixa_API();
                $resultado_json = $api->get_concurso($loteria, $concurso);
                $resultado = json_decode($resultado_json, true); 
                if (!$resultado || isset($resultado['error'])) {
                    return false;
                }
                $data_concurso = $resultado['data'];
                $day = lotcaixa_what_day_week($data_concurso);
                $head_title = 'Concurso '.$resultado['concurso'].' • '.$day. ' '.$data_concurso;
                $concurso_numero = $resultado['concurso'];
                $dezenas = $resultado['dezenas'];
                $valor = $resultado['valorEstimadoProximoConcurso'];
                $valor_estimado = number_format($valor, 2, ',', '.');
                $premiacoes = $resultado['premiacoes'];
            }

        }
        
        return array(
            'check_concurso' => $check_concurso,
            'concurso_numero' => $concurso_numero,
            'data' => array(
                'head_title' => $head_title,
                'data_concurso' => $data_concurso,
                'valor_estimado' => $valor_estimado,
                'dezenas' => $dezenas,
                'premiacoes' => $premiacoes
            )
        );
    }

    // Criar novo registro de CPT loterias
    public function lotcaixa_insert_new_loteria($args){    
        $post_id = wp_insert_post(
            array(
                'post_title'   => $args['titulo'],
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'loterias',
                'meta_input'   => array(
                    'loteria'       => $args['loteria'],
                    'concurso'      => $args['concurso'],
                    'data_concurso' => $args['data_concurso'],
                    'valor_estimado' => $args['valor_estimado'],
                    'dezenas'       => $args['dezenas'],                
                    'premiacoes' => $args['premiacoes'],
                ),
            )
        );

    }
    
    
    
}

new LoteriasCaixa();
