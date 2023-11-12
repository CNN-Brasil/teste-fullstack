<?php

namespace Cnnbr\TesteFullstack\Classes;

/**
 * Undocumented class
 */
class ProviderLoteria {

    private string  $api_url;
    public  string  $response;
    public  string  $loteria;
    public  string  $concurso;

    /**
     * Undocumented function
     *
     * @param LoteriaDTO $loteriaDTO
     */
    public function __construct( LoteriaDTO $loteriaDTO ) 
    {
        // Parameters to constructor
        $this->api_url = esc_url_raw('https://loteriascaixa-api.herokuapp.com/api', 'https');
        $this->loteria = $loteriaDTO->getLoteria();
        $this->concurso = $loteriaDTO->getConcurso();

        $this->fetchData();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function fetchData()
    {

        $remoteGet = wp_safe_remote_get( $this->urlWithParameter() );

        if ( is_null($remoteGet) || is_wp_error( $remoteGet ) ) {
            $this->response = '';
            return;
        }

        $this->response = wp_remote_retrieve_body( $remoteGet );
        
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function urlWithParameter()
    {
        return "{$this->api_url}/{$this->loteria}/{$this->concurso}";
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function response()
    {
        return json_decode($this->response);
    }

}