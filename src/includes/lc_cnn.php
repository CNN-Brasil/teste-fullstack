<?php
/**
 * Classe abstrata para funcionalidades.
 * @package LoteriasCaixaCNN
 */
abstract class lc_cnn {

	/**
	 * Array para os dados finais da loteria.
	 *
	 * @var array
	 */
	public array $loteria = array();

	/**
	 * Parâmetros para o indentificador da loteria e o concurso.
	 *
	 * @var array
	 */
	public array $params = array(
		'loteria'  => '',
		'concurso' => 'latest',
	);

    /**
     * indentificador da loteria a serem usados.
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
	 * Adiciona o shortcode 'loterias'.
	 */
	public function __construct() {
		add_shortcode( 'loterias', array( $this, 'create_shortcode' ) );
	}

	/**
	 * Verifica se os dados do concurso já estão armazenados em banco.
	 *
	 * @param string $loteria Identificador loteria.
	 * @param string|int $concurso Número concurso.
	 * @return object Resultado consulta.
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
	}

	/**
	 * @param array $loteria Dados da loteria a serem armazenados.
	 */
	protected function set_array_loteria(array $loteria ) : void {

		$loteria['diaSemana'] = loteriasCaixaCNN_Utils::get_week_day( $loteria['data'] );
		$loteria['valorEstimadoProximoConcurso'] = loteriasCaixaCNN_Utils::format_currency( $loteria['valorEstimadoProximoConcurso'] );

		$this->loteria = $loteria;
	}

	/**
	 * Define o identificador.
	 * @param string $loteria Identificador da loteria.
	 */
	protected function set_loteria(string $loteria ) : void {

		$this->params['loteria'] = $loteria;
	}

	/**
	 * Número do concurso.
	 * @param string|int $concurso Número do concurso ou 'latest'.
	 */
	protected function set_concurso(string $concurso ) : void {
		$this->params['concurso'] = $concurso;
	}
}