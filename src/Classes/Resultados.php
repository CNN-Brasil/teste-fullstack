<?php 
namespace Cnnbr\TesteFullstack\Classes;

/**
 * Resultados
 */
class Resultados extends HandleConcurso {

    public function __construct(LoteriaDTO $loteriaDTO ) 
    {
        $this->loteriaDTO = $loteriaDTO; 
        $this->loteria = $loteriaDTO->getLoteria();
        $this->concurso = $loteriaDTO->getConcurso();

        $this->handleConcurso();
    }

    /**
     * Results
     *
     * @return void
     */
    public function getResults()
    {

        return $this->response;
    }
}
