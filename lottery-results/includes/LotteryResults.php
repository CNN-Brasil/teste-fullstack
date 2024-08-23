<?php

namespace LotteryResults\includes;

/**
 * Classe principal do plugin.
 */
class LotteryResults {


	/**
	 * Construtor da classe.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type_lotteries' ) );
		add_action( 'init', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Enfileira os estilos necessários para o plugin.
	 */
	public function enqueue_styles(): void {

		wp_enqueue_style(
			'inter-font',
			'https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'lottery-results-styles',
			plugin_dir_url( __FILE__ ) . '../css/lottery-results.css',
			array(),
			'1.0.0',
			'all'
		);
	}

	/**
	 * Registra o tipo de post "Lotteries".
	 */
	public function register_post_type_lotteries(): void {
		$labels = array(
			'name'                  => _x( 'Lotteries', 'Nome geral do tipo de post', 'lottery-results' ),
			'singular_name'         => _x( 'Lottery', 'Nome singular do tipo de post', 'lottery-results' ),
			'menu_name'             => _x( 'Lotteries', 'Texto do Menu Admin', 'lottery-results' ),
			'name_admin_bar'        => _x( 'Lottery', 'Adicionar novo na barra de ferramentas', 'lottery-results' ),
			'add_new'               => __( 'Add New', 'lottery-results' ),
			'add_new_item'          => __( 'Add New Lottery', 'lottery-results' ),
			'new_item'              => __( 'New Lottery', 'lottery-results' ),
			'edit_item'             => __( 'Edit Lottery', 'lottery-results' ),
			'view_item'             => __( 'View Lottery', 'lottery-results' ),
			'all_items'             => __( 'All Lotteries', 'lottery-results' ),
			'search_items'          => __( 'Search Lotteries', 'lottery-results' ),
			'parent_item_colon'     => __( 'Parent Lotteries:', 'lottery-results' ),
			'not_found'             => __( 'No lotteries found.', 'lottery-results' ),
			'not_found_in_trash'    => __( 'No lotteries found in Trash.', 'lottery-results' ),
			'featured_image'        => __( 'Featured Image', 'lottery-results' ),
			'set_featured_image'    => __( 'Set featured image', 'lottery-results' ),
			'remove_featured_image' => __( 'Remove featured image', 'lottery-results' ),
			'use_featured_image'    => __( 'Use as featured image', 'lottery-results' ),
			'archives'              => __( 'Lottery archives', 'lottery-results' ),
			'insert_into_item'      => __( 'Insert into lottery', 'lottery-results' ),
			'uploaded_to_this_item' => __( 'Uploaded to this lottery', 'lottery-results' ),
			'filter_items_list'     => __( 'Filter lotteries list', 'lottery-results' ),
			'items_list_navigation' => __( 'Lotteries list navigation', 'lottery-results' ),
			'items_list'            => __( 'Lotteries list', 'lottery-results' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'lotteries' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'custom-fields' ), // Adicione mais supports se necessário
		);

		register_post_type( 'lotteries', $args );
	}

	/**
	 * Exibe os resultados da loteria através de um shortcode.
	 *
	 * @param array $atts Atributos do shortcode.
	 * @return string HTML dos resultados da loteria.
	 */
	public function display_lottery_results( array $atts ): string {
		$atts = shortcode_atts(
			array(
				'lottery' => 'megasena',
				'contest' => 'latest',
			),
			$atts
		);

		$lottery          = sanitize_text_field( $atts['lottery'] );
		$contest          = sanitize_text_field( $atts['contest'] );
		$existing_post_id = $this->get_lottery_post_id( $lottery, $contest );

		if ( 'latest' !== $contest && $existing_post_id ) {
			$data = get_post_meta( $existing_post_id, '_lottery_results', true );
		} else {
			$data = $this->fetch_lottery_results_from_api( $lottery, $contest );

			if ( 'latest' !== $contest && ! $existing_post_id && ! empty( $data ) ) {
				$this->save_lottery_results( $lottery, $contest, $data );
			}
		}

		if ( is_wp_error( $data ) ) {
			return $data->get_error_message();
		}

		return $this->format_lottery_results( $lottery, $contest, $data );
	}

	/**
	 * Obtém o ID do post de um resultado de loteria existente.
	 *
	 * @param string $lottery Nome da loteria.
	 * @param string $contest Número do concurso ou "latest".
	 * @return int|false ID do post se encontrado, falso caso contrário.
	 */
	private function get_lottery_post_id( string $lottery, string $contest ): int|false {
		$args = array(
			'post_type'      => 'lotteries',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => '_lottery_name',
					'value' => $lottery,
				),
				array(
					'key'   => '_contest_number',
					'value' => $contest,
				),
			),
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			$query->the_post();
			return get_the_ID();
		} else {
			return false;
		}

		wp_reset_postdata();
	}

	/**
	 * Busca os resultados da loteria na API.
	 *
	 * @param string $lottery Nome da loteria.
	 * @param string $contest Número do concurso ou "latest".
	 * @return array|WP_Error Resultados da loteria se bem-sucedido, WP_Error caso contrário.
	 */
	private function fetch_lottery_results_from_api( string $lottery, string $contest ): array|\WP_Error {
		$cache_key = 'lottery_results_' . md5( $lottery . '_' . $contest );
		$data      = get_transient( $cache_key );

		if ( ! $data ) {
			$api_url  = 'https://loteriascaixa-api.herokuapp.com/api/' . $lottery . '/' . $contest;
			$response = wp_remote_get( $api_url );

			if ( is_wp_error( $response ) ) {
				return new \WP_Error( 'api_error', esc_html__( 'Erro ao buscar resultados da loteria na API.', 'lottery-results' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
				return new \WP_Error( 'invalid_response', esc_html__( 'Resposta inválida da API.', 'lottery-results' ) );
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				return new \WP_Error( 'json_error', esc_html__( 'Erro ao decodificar resposta JSON.', 'lottery-results' ) );
			}

			if ( isset( $data['loteria'] ) ) {
				set_transient( $cache_key, $data, HOUR_IN_SECONDS * 12 );
			} else {
				return new \WP_Error( 'invalid_data', esc_html__( 'Formato de dados inválido da API.', 'lottery-results' ) );
			}
		}

		return $data;
	}

	/**
	 * Salva os resultados da loteria no tipo de post "Lotteries".
	 *
	 * @param string $lottery Nome da loteria.
	 * @param string $contest Número do concurso.
	 * @param array  $results Resultados da loteria.
	 * @return int|WP_Error ID do post se bem-sucedido, WP_Error caso contrário.
	 */
	private function save_lottery_results( string $lottery, string $contest, array $results ): int|\WP_Error {
		$post_data = array(
			'post_title'  => sprintf( __( '%1$s - Concurso %2$s', 'lottery-results' ), ucfirst( $lottery ), $contest ),
			'post_type'   => 'lotteries',
			'post_status' => 'publish',
			'meta_input'  => array(
				'_lottery_name'    => $lottery,
				'_contest_number'  => $contest,
				'_lottery_results' => $results,
			),
		);

		$post_id = wp_insert_post( $post_data );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		return $post_id;
	}

	/**
	 * Formata os resultados da loteria de acordo com o layout do Figma.
	 *
	 * @param string $lottery Nome da loteria.
	 * @param string $contest Número do concurso ou "latest".
	 * @param array  $results Resultados da loteria.
	 * @return string HTML formatado dos resultados.
	 */
	private function format_lottery_results( string $lottery, string $contest, array $data ): string {
		ob_start();

		include plugin_dir_path( __FILE__ ) . '../templates/lottery-results.php';

		return ob_get_clean();
	}

	/**
	 * Registra o shortcode.
	 */
	public function register_shortcode(): void {
		add_shortcode( 'lottery-results', array( $this, 'display_lottery_results' ) );
	}
}
