<?php
namespace Cnnbr\TesteFullstack\Classes;

use WP_Query;

abstract class HandleConcurso {

    public ProviderLoteria $providerLoteria;
    public LoteriaDTO $loteriaDTO;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    public $data;
    public object   $response;
    public string   $loteria;
    public string   $concurso;

    public function __construct(){}

    /**
     * Undocumented function
     *
     * @return void
     */
    public function handleConcurso()
    {
        $hasExistConcurso = $this->hasExistConcurso();
    
        if( !empty($hasExistConcurso) ){
            $this->response = get_post_meta($hasExistConcurso[0], 'json', true);
            $this->response->diaSemana = $this->formatDayWeek($this->response->data);
            return $this->response;
        }

        $this->providerLoteria = new ProviderLoteria($this->loteriaDTO);
        $this->response = (object) $this->providerLoteria->response();
        $this->response->diaSemana = $this->formatDayWeek($this->response->data);

        $this->insertConcurso();
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function insertConcurso()
    {
        if( empty($this->response) ){
            return;
        }
        wp_insert_post(array(
            'post_title'    => "{$this->response->loteria} / Concurso: {$this->response->concurso}",
            'post_status'   => 'publish',
            'post_type'     => 'loterias',
            'meta_input'   => array(
                'loteria' => $this->response->loteria,
                'concurso'   => $this->response->concurso,
                'data'   => $this->response->data,
                'json'   => $this->response,
            ),
        ));

        // Save latest data
        if($this->concurso == 'latest') {
            update_option("latest_{$this->response->loteria}", $this->response->concurso, false);
        }
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function hasExistConcurso()
    {
        // Warning:message
        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_query
        $query = new WP_Query(array(
            'post_type' => 'loterias',
            'posts_per_page' => 1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'loteria',
                    'value'   => $this->loteria,
                    'compare' => '=',
                ),
                array(
                    'key'     => 'concurso',
                    'value'   => $this->handleConcursoLatest(),
                    'compare' => '=',
                ),
            ),

            // TODO: Efficient Database Queries WP_Query
            'no_found_rows' => true, // useful when pagination is not needed.
            'update_post_meta_cache' => true, // useful when post meta will not be utilized.
            'update_post_term_cache' => false, // useful when taxonomy terms will not be utilized.
            'fields' => 'ids', // useful when only the post IDs are needed (less typical).
        ));
        
        return $query->posts;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function handleConcursoLatest()
    {
        return $this->concurso  != 'latest' ? $this->concurso : get_option("latest_{$this->loteria}");
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function formatDayWeek( $dayWeek ) :string
    {
        $data = gmdate('Y-m-d', strtotime($dayWeek));
        $dateTime = new \DateTime($data);
        $dayWeek = array(
            'Monday'    => 'Segunda-feira',
            'Tuesday'   => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday'  => 'Quinta-feira',
            'Friday'    => 'Sexta-feira',
            'Saturday'  => 'Sábado',
            'Sunday'    => 'Domingo'
        );

        return $dayWeek[$dateTime->format('l')];
    }

}