<?php
/**
 * Classe Loteria
 *
 * Esta classe é responsável por acessar a API das loterias, salvar um cache dessa requisição
 * e também salvar no post type do tipo "loterias".
 *
 * @package loterias\classes
 * @author Seu Nome
 * @version 1.0
 * @since 2024-08-11
 */

namespace loterias\classes;

/**
 * Class Loteria
 *
 * Esta classe lida com a interação com a API das loterias, incluindo o
 * cache das respostas e a manipulação dos posts no tipo "loterias".
 *
 * @package loterias\classes
 */
class Loteria {

	/**
	 * Número do concurso.
	 *
	 * @var int
	 */
	public $concurso;


	/**
	 * Qual a Loteria.
	 *
	 * @var string
	 */
	public $loteria;


	/**
	 * Essa variável vai retornar a array com os dados montados.
	 *
	 * @var string
	 */
	public $dados;


	/**
	 * Aqui eu informo se será ou não necessária a conversão de algum dados
	 *
	 * @var string
	 */
	private $convert;




	/**
	 * Nessa  funçãao, eu vou fazer os acessos à api, e salvar os dados em custon post e cache.
	 */
	public function acessa_api() {
		$concurso_info = '';
		/**Essa variável irá  no final do paramêtro de api para buscar os dados*/
		if ( ! empty( $this->concurso ) ) {
			$concurso_info = '/' . $this->concurso;
		} elseif ( 'ultimo' === $this->concurso ) {
			$concurso_info = '/latest';
		} else {
			$this->concurso = 'latest';
			$concurso_info  = '/latest';
		}
		$post_title = $this->loteria . ' - Concurso ' . $this->concurso;
		/**Nome do concurso que será usado para dar nome ou ao post, ocmo ao cachê */

		/**Aqui eu vou ver primeiro, se o numero do concurso foi "setado". */
		/**Aqui eu tbm vejo se existe algo registrado nos "posts types" se tiver eu lanço eles ao invés de olhar a api.  */
		if ( isset( $this->concurso ) ) :
			// informanndo que  eu quero que o conteudo deve ser convertido para array.
			$this->convert = 1;
			// Verifica se já existe um post com este título.
			$existing_post_content = $this->get_existing_post_content( $post_title );
			if ( $existing_post_content ) {
					echo wp_kses_post( $existing_post_content );
					return $this;
			}
		endif;

		$url = 'https://loteriascaixa-api.herokuapp.com/api/' . $this->loteria . $concurso_info;
		// Definindo o nome do arquivo de cache com base na URL !
		$cache_dir = plugin_dir_path( __FILE__ ) . 'cache/';
		/** Endereço do arquivo de cachê */
		$cache_file = $cache_dir . md5( $url ) . '.json';
		/***Nome do arquivo json que vai ter o cachê da consulta da  api */
		// Verifica se o diretório de cache existe, se não, cria com permissões !
		if ( ! file_exists( $cache_dir ) ) {
			if ( ! mkdir( $cache_dir, 0755, true ) ) {
				error_log( 'Falha ao criar o diretório de cache:' . $cache_dir );
				return;
			}
		}
		// Verifica se o cache existe e é recente (por exemplo, 1 hora)!
		if ( file_exists( $cache_file ) && ( time() - filemtime( $cache_file ) < 3600 ) ) {
			// Lê os dados do cache!
			$data = json_decode( file_get_contents( $cache_file ), true );
		} else {
			// Inicia uma nova sessão cURL!
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$response = curl_exec( $ch );
			if ( curl_errno( $ch ) ) {
				echo 'Erro: ' . esc_html( curl_error( $ch ) );
			} else {
				$data = json_decode( $response, true );
				// Salva a resposta em cache.
				if ( file_put_contents( $cache_file, $response ) === false ) {
					error_log( 'Falha ao escrever no arquivo de cache: ' . $cache_file );
				} else {
					/** Define as permissões do arquivo de cache para leitura e escrita (644). */
					chmod( $cache_file, 0644 );
				}
			}
			curl_close( $ch );
		}
		// Atualiza o conteúdo do post com a nova resposta da API!
		$this->grid( $data );

		return $this;
	}










	/**Essa função vai montar os elementos html para poder assim, dispor na tela os resultados.
	 *
	 * @param string $data  é o parametro que vai ser recebido para ele poder montar o html.
	 */
	private function grid( $data ) {
		if ( ! isset( $data[0] ) ) {
			$dados = $data;
			$data  = array( $dados );
		}
		$loteria = $this->loteria;
		$html    = '<div id="resultados" class="' . $loteria . ' font">';
		$linha   = 0;
		foreach ( $data as $dd ) {
			if ( ! isset( $dd['concurso'] ) ) {
				$dd['concurso'] = 'não informado';
			}
			if ( ! isset( $dd['data'] ) ) {
				$dd['data'] = 'não informada';
			}
			if ( ! isset( $dd['dezenasOrdemSorteio'] ) ) {
				$dd['dezenasOrdemSorteio'] = array();
			}
			if ( ! isset( $dd['valorAcumuladoConcursoEspecial'] ) ) {
				$dd['valorAcumuladoConcursoEspecial'] = null;
			}
			if ( ! isset( $dd['premiacoes'] ) ) {
				$dd['premiacoes'] = array();
			}
			// Cria o título do post.
			$post_title = $loteria;
			if ( 'não informado' !== $dd['concurso'] ) {
				$post_title .= ' - Concurso ' . $dd['concurso'];
			}
			// Se o post não existir, cria o novo conteúdo!
			$html .= '<h2> Concurso: ' . $dd['concurso'] . ' ' . $dd['data'] . '</h2>';
			$html .= '<ul id="dezenas">';
			foreach ( $dd['dezenasOrdemSorteio'] as $d ) {
				$html .= '<li>' . $d . '</li>';
			}
			$html .= '</ul>';
			$html .= '<div> ';
			$html .= '</div>';
			$html .= '<h3><p>Premio: </p> <p> R$' . number_format( $dd['valorAcumuladoConcursoEspecial'], 2, ',', '.' ) . '</p></h3>';
			$html .= '<table>';
			$html .= '<thead>';
			$html .= '<td>Faixa: </td>';
			$html .= '<td>Ganhadores: </td>';
			$html .= '<td>Premio: </td>';
			$html .= '</thead>';
			foreach ( $dd['premiacoes'] as $pp ) {
				$html .= '<tr>';
				$html .= '<td>' . $pp['faixa'] . '</td>';
				$html .= '<td>' . $pp['ganhadores'] . '</td>';
				$html .= '<td>R$' . number_format( $pp['valorPremio'], 2, ',', '.' ) . ' </td>';
				$html .= '</tr>';
			}
			$html .= '</table></div>';
			// Salva o novo post.
			$this->save_to_custom_post( $post_title, $html );
			++$linha;
		}
		$html       .= '</div>';
		$this->dados = $html;
	}







	/**
	 * Verifica se o post já existe.
	 *
	 * @param string $title The title of the post.
	 */
	private function get_existing_post_content( $title ) {
		$existing_post = get_page_by_title( $title, OBJECT, 'loterias' );

		if ( $existing_post ) { // Verifica se o post foi encontrado.
			// Se o post já existir e $this->convert for 1, retorna o conteúdo do post.
			if ( 1 === $this->convert ) {
				return $existing_post->post_content;
			}
			// Retorna o conteúdo do post.
			return $existing_post->post_content;
		}
		// Retorna false se o post não existir.
		return false;
	}








	/**
	 * Função que vai salvar as informações no custom post do tipo loterias.
	 *
	 * @param string $title   titulo do post type.
	 * @param string $content conteudo do post type.
	 **/
	private function save_to_custom_post( $title, $content ) {
		// Verifica se o post já existe.
		$existing_post = get_page_by_title( $title, OBJECT, 'loterias' );
		if ( $existing_post ) {
			// Se o post já existir, apenas atualiza o conteúdo.
			$post_id   = $existing_post->ID;
			$post_data = array(
				'ID'           => $post_id,
				'post_content' => $content,
			);
			wp_update_post( $post_data );
		} else {
			// Se o post não existir, cria um novo.
			$post_data = array(
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'loterias',
			);
			$post_id   = wp_insert_post( $post_data );
		}
	}
}
