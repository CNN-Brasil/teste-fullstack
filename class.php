<?php
if (!defined('ABSPATH')) {
    exit();
}

class LoteriasCaixa{

    public function __construct() { 
		add_action('wp_enqueue_scripts', array($this, 'lotcaixa_front_scripts'));
        add_shortcode( 'loterias', array( $this, 'lotcaixa_render_shortcode' ) );		
	}
    /*
    *
    * Registra os assets do plugin.
    *     
    */
    public function lotcaixa_front_scripts() {			
		// code CSS
		wp_enqueue_style( 'lotcaixa-style', esc_url(LOTCAIXA_URL).'src/assets/css/lotcaixa.css', array(), esc_html(LOTCAIXA_VERSION) );				
		
		// code JS		
		wp_enqueue_script( 'lotcaixa-script', esc_url(LOTCAIXA_URL).'src/assets/js/lotcaixa.js', array(), esc_html(LOTCAIXA_VERSION), true );			
		wp_localize_script( 'lotcaixa', 'lotcaixa_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) );        
	}
    /*
     * Renderiza o shortcode para exibir informações sobre uma loteria específica.
     *
     * @param array $atts Atributos do shortcode.
     * - loteria (string): O nome da loteria, por exemplo, "megasena".
     * - concurso (string|int): O número do concurso da loteria, podendo ser um número ou "ultimo".
     * @return string HTML do shortcode.
    */    
	public function lotcaixa_render_shortcode( $atts ) {
        // Attributes
        $atts = shortcode_atts(
            array(            
                'loteria' => '',
                'concurso' => 'ultimo'
            ), $atts
        ); 
        
        $loteria = $atts['loteria'];
        $concurso = !empty($atts['concurso']) ? $atts['concurso'] : 'ultimo'; //var_dump($concurso);

        // Verifica se o valor de $atts['loteria'] está informado
        if ( empty( $loteria ) ) {
            return '<p class="error">' . esc_html__( 'O parâmetro para loteria é obrigatório!', 'loterias-caixa' ) . '</p>';
        }
    
        // Verifica se o valor de $atts['loteria'] está dentro do array de loterias válidas
        if ( ! in_array( $loteria, LOTERIAS_VALIDAS ) ) {
            return '<p class="error">' . esc_html__( 'O parâmetro para loteria é inválido!', 'loterias-caixa' ) . '</p>';
        }
    
        // Verifica se o valor de $atts['concurso'] contém apenas números ou a string 'ultimo'
        if (  $concurso != 'ultimo' && ! preg_match( '/^\d+$|^ultimo$/i', $concurso ) ) {
            return '<p class="error">' . esc_html__( 'O parâmetro para concurso é inválido!', 'loterias-caixa' ) . '</p>';
        }

        $api            = new LoteriasCaixa_API();
		$resultado_json = $api->obter_dados_concurso( $loteria, $concurso );
		$resultado      = json_decode( $resultado_json, true ); 
        //var_dump( $resultado );

		if ( ! $resultado || isset( $resultado['erro'] ) ) {
			return '<p class="error">Não foi possível obter os resultados. Por favor, tente novamente mais tarde.</p>';
		}
        // Assets do plugins      
        wp_enqueue_style('lotcaixa-style');   

        $data_api = $resultado['data']; 
        //var_dump( $data_api );		
        
       // Converte a data para um formato manipulável
$data_convertida = strtotime($data_api); var_dump( $dia_da_semana );

// Obtém o dia da semana
$dia_da_semana = date('l', $data_convertida); var_dump( $dia_da_semana );

// Array associativo para traduzir o nome do dia da semana para português
$traducao_dias = array(
    'Monday' => 'Segunda-feira',
    'Tuesday' => 'Terça-feira',
    'Wednesday' => 'Quarta-feira',
    'Thursday' => 'Quinta-feira',
    'Friday' => 'Sexta-feira',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
);

// Traduz o nome do dia da semana
$dia_da_semana_traduzido = $traducao_dias[$dia_da_semana]; var_dump($dia_da_semana_traduzido);

// Formata a data conforme desejado
$data_formatada = $dia_da_semana_traduzido . ' ' .$data_api;

        $valor  = $resultado['valorEstimadoProximoConcurso'];
        $valor_formatado = number_format( $valor, 2, ',', '.' );

        $html = '';
    
        $html .= '<div id="loteria" class="loterias-wrap loteria-'.$loteria.'">';
        $html .= '<div id="concurso" class="col-concurso">';
        $html .= "<div class='cabecalho'> Concurso {$resultado['concurso']} • {$data_formatada}</div>";
        $html .= "<div class='dezenas'>";
        $html .= "<ul class='dezenas-itens'>";
                foreach ( $resultado['dezenas'] as $dezena ) {
                    $html .= "<li>{$dezena}</li>";
                }
        $html .= '</ul>';
        $html .= '</div>';        
        $html .= "<div class='premiacao'>";
        $html .= '<p>PRÊMIO</p>';   
        $html  .= "<p>R$ {$valor_formatado}</p>";
        $html  .= '</div>';        
        $html  .= '<table id="table-premiacoes">';
        $html  .= "<tr><th>Faixas</th><th>Ganhadores</th><th>Prêmio</th></tr>";
                foreach ( $resultado['premiacoes'] as $premiacao ) {
                    $html .= "<tr><td>{$premiacao['descricao']}</td><td>{$premiacao['ganhadores']}</td><td>R$ {$premiacao['valorPremio']}</td></tr>";
                }
        $html .= '</table>';
        $html .= '</div>';                
        $html .= '</div>';            
        $html .= '</div>';

        return $html;
    }

    /*
    *    
    * Aceitos
    * [loterias loteria="megasena"] = Aceito
    * [loterias loteria="megasena" concurso=""] = Aceito
    * [loterias loteria="megasena" concurso="ultimo"] = Aceito
    * Erros
    * [loterias loteria="megasena" concurso="opa"] = Erro no concurso
    * [loterias loteria="bicho" concurso="teste"] = Erro na loteria
    * [loterias loteria="tigrinho" concurso="teste"] = Erro na loteria
    * [loterias loteria="" concurso="teste"] = Erro na loteria
    * [loterias concurso="teste"] = Erro na loteria
    *
    */
    
}

new LoteriasCaixa();
