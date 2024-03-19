<?php
	/*
		Plugin Name: Loterias Plugin
		Description: Plugin para consulta e exibição de resultados de loterias.
		Version: 1.0
		Author: Renato Poli
	*/
	
	/*
		DISCLAIMER DO PROJETO:
		
		ADOTEI A COR #8B008B NO PARAMETRO maismilionaria POIS NO FIGMA NÃO EXISTIA ESSE ITEM
	*/
	
	class Loterias_Plugin {
		
		// Construtor da classe
		public function __construct() {
			add_action('wp_enqueue_scripts', array($this, 'carregar_estilos_loterias'));
			add_action('init', array($this, 'criar_post_type_loterias'));
			add_shortcode('exibir_resultados_loterias', array($this, 'exibir_resultados_loterias'));
		}
		
		// Função para carregar os estilos CSS do plugin
		public function carregar_estilos_loterias() {
			// Define o caminho para o arquivo CSS
			$css_file_url = plugin_dir_url(__FILE__) . 'css/style.min.css';
			
			// Adiciona o arquivo CSS à fila de estilos
			wp_enqueue_style('loterias-style', $css_file_url, array(), '1.0', 'all');
		}
		
		// Criação do post type "Loterias"
		public function criar_post_type_loterias() {
			register_post_type('loterias', array(
            'labels' => array(
			'name' => __('Loterias'),
			'singular_name' => __('Loteria')
            ),
            'public' => true,
            'has_archive' => true,
			));
		}
		
		// Função para buscar resultados da API
		private function buscar_resultados_loterias($loteria) {
			$url = "https://loteriascaixa-api.herokuapp.com/api/$loteria/latest"; // URL da API
			// Faz a requisição à API
			$response = wp_remote_get($url);
			if (is_wp_error($response)) {
				return false;
			}
			$body = wp_remote_retrieve_body($response);
			$data = json_decode($body, true);
			return $data;
		}
		
		// Função para exibir os resultados no front-end
		public function exibir_resultados_loterias($atts) {
			$atts = shortcode_atts(array(
            'loteria' => 'megasena'
			), $atts);
			
			$loteria = strtolower($atts['loteria']); // Convertendo para minúsculas para comparação
			$resultados = $this->buscar_resultados_loterias($loteria);
			
			// Verificar se o post já existe
			$post_existente = get_posts(array(
            'post_type' => 'loterias',
            'meta_key' => 'concurso',
            'meta_value' => $resultados['concurso']
			));
			
			// Se não existe, adicionar como novo post
			if (empty($post_existente)) {
				$post_id = wp_insert_post(array(
                'post_type' => 'loterias',
                'post_status' => 'publish',
                'post_title' => "Resultado da $loteria - Concurso " . $resultados['concurso'],
				));
				
				// Salvar os meta campos
				if ($post_id) {
					update_post_meta($post_id, 'concurso', $resultados['concurso']);
					update_post_meta($post_id, 'data', $resultados['data']);
					update_post_meta($post_id, 'local', $resultados['local']);
					// Adicione outros meta campos conforme necessário
				}
			}
			
			// Definição das cores dinâmicas
			$cores = array(
            'megasena' => '#2D976A',
            'quina' => '#261383',
            'lotofacil' => '#921788',
            'lotomania' => '#F58123',
            'timemania' => '#3DAF3E',
            'duplasena' => '#A41628',
            'federal' => '#133497',
            'diadesorte' => '#CA8536',
            'supersete' => '#A9CF50',
            'maismilionaria' => '#8B008B'
			);
			
			// Verifica se a loteria passada é válida
			if (!isset($cores[$loteria])) {
				return "<p>Loteria inválida.</p>";
			}
			
			// Aplica as cores dinâmicas
			$cor_fundo = $cores[$loteria];
			$cor_texto = '#ffffff';
			
			ob_start();
			
			if ($resultados) {
				
				// Manipulação da data para pegar o dia da semana
				$diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
				$data_original = $resultados['data'];
				$data_formatada = date("Y-m-d", strtotime(str_replace('/', '-', $data_original)));
				$diasemana_numero = date('w', strtotime($data_formatada));
				
				echo "<h2>Resultados da $loteria</h2>";
				echo "<div class='titulo_do_concurso' style='background-color: $cor_fundo; color: $cor_texto;'>Concurso " . $resultados['concurso'] . " • " . $diasemana[$diasemana_numero] . '&nbsp;&nbsp;' . $resultados['data'] . "</div>";
				
				echo '<div class="resultados">';
				foreach ($resultados['dezenas'] as $dezena) {
					echo "<span style='background-color: $cor_fundo;' class='item_dezenas'>$dezena</span>";
				}
				
				echo "</div>";
				
				echo '<div class="box_premio">';
				echo '<div class="titulo_premio">Prêmio</div>';
				echo '<div class="valor_premio">R$ ' . number_format($resultados['premiacoes'][0]['valorPremio'], 2, ',', '.') . '</div>';
				echo '</div>';
				
				echo "<div class='grid'>";
				
				// Título das colunas
				echo '<div class="titulo_grid" style="background-color: '.$cor_fundo.'">Faixas</div>'; // Coluna 1
				echo '<div class="titulo_grid" style="background-color: '.$cor_fundo.'">Ganhadores</div>'; // Coluna 2
				echo '<div class="titulo_grid" style="background-color: '.$cor_fundo.'">Prêmio</div>'; // Coluna 3
				
				// Dados
				foreach ($resultados['premiacoes'] as $premiacao) {
					echo "<div class='dados'>" . $premiacao['descricao'] . "</div>"; // Coluna 1
					echo "<div class='dados'>" . $premiacao['ganhadores'] . "</div>"; // Coluna 2
					echo "<div class='dados'>R$ " . number_format($premiacao['valorPremio'], 2, ',', '.') . "</div>"; // Coluna 3
				}
				
				echo "</div>";
				} else {
				echo "<p>Nenhum resultado encontrado.</p>";
			}
			return ob_get_clean();
		}
	}
	
	// Inicia o plugin
	new Loterias_Plugin();
