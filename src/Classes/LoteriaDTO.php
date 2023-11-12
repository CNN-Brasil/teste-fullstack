<?php

namespace Cnnbr\TesteFullstack\Classes;

/**
 * Undocumented class
 */
class LoteriaDTO {
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $loteria;
    private $concurso;

    /**
     * Undocumented function
     *
     * @param string $loteria
     * @param integer $concurso
     */
    public function __construct( string $loteria, string $concurso) {
        $this->loteria = $loteria;
        $this->concurso = empty($concurso) ? 'latest' : $concurso;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLoteria() :string
    {
        return $this->loteria;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getConcurso() :string 
    {
        return $this->concurso;
    }
}