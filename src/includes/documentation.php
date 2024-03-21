<?php
if (!defined('ABSPATH')) {
    exit();
}
function lotcaixa_documentation_page_content(){
    echo 'ola';
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