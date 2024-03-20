<?php
/**
 * Classe base abstrata para a criação de funcionalidades.
 *
 * @package LCNN
 */
abstract class LCNN_Base {


	/**
	 * Array para os dados finais da loteria.
	 *
	 * @var array
	 */
	public array $loteria = array();


	/**
	 * Parâmetros padrão para o indentificador da loteria e o numero do concurso.
	 *
	 * @var array
	 */
	public array $params = array(
		'loteria'  => '',
		'concurso' => 'latest',
	);

    /**
     * Listagem dos indentificador da loteria a serem usados.
     *
     * @var array
     */
    public array $loterias = array(
        "maismilionaria",
        "megasena",
        "lotofacil",
        "quina",
        "lotomania",
        "timemania",
        "duplasena",
        "federal",
        "diadesorte",
        "supersete"
    );

	/**
	 * Construtor adiciona o shortcode 'loterias'.
	 */
	public function __construct() {
		add_shortcode( 'loterias', array( $this, 'create_shortcode' ) );
	}

	/**
	 * Checa se os dados do concurso já estão armazenados em banco.
	 *
	 * @param string     $loteria Identificador da loteria.
	 * @param string|int $concurso Número do concurso.
	 * @return object Resultado da consulta.
	 */
	public function is_value_stored(string $loteria, string $concurso ) : object {
		return new \WP_Query(
			array(
				'post_type'      => 'loterias',
				'meta_query'     => array(
					array(
						'key'     => 'loteria',
						'value'   => $loteria,
						'compare' => '=',
					),
					array(
						'key'     => 'concurso',
						'value'   => $concurso,
						'compare' => '=',
					),
				),
				'posts_per_page' => 1,
			)
		);

	}//end is_value_stored()

	/**
	 * Setter que define o array da loteria com alguns dados formatados.
	 *
	 * @param array $loteria Dados da loteria a serem armazenados.
	 */
	protected function set_array_loteria(array $loteria ) : void {

		$loteria['diaSemana'] = LCNN_Utils::get_week_day( $loteria['data'] );

		$loteria['valorEstimadoProximoConcurso'] = LCNN_Utils::format_currency( $loteria['valorEstimadoProximoConcurso'] );

		$this->loteria = $loteria;
	}//end set_array_loteria()

	/**
	 * Define o identificador da loteria.
	 *
	 * @param string $loteria Identificador da loteria.
	 */
	protected function set_loteria(string $loteria ) : void {

		$this->params['loteria'] = $loteria;
	}//end set_loteria()

	/**
	 * Define o número do concurso.
	 *
	 * @param string|int $concurso Número do concurso ou 'latest'.
	 */
	protected function set_concurso(string $concurso ) : void {
		$this->params['concurso'] = $concurso;
	}//end set_concurso()
}//end class
