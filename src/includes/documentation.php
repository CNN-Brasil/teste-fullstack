<?php
if (!defined('ABSPATH')) {
    exit();
}
function lotcaixa_documentation_page_content(){
    ob_start();
    ?>
    <div id="page-documentation" class="container">
        <h1>#Documentação - Version: 1.0</h1>

        <h2> Instruções para utilização</h2>

        <p>Baixe o plugin, descompacte-o e copie para dentro da pasta plugin de seu site Wordpress.</p>

        <p>A pasta vendor foi adicionada ao gitignore, portanto, é necessário rodar o composer install.</p>

        <p>Navegue até a pasta do plugin, e digite o seguinte comando:</p>

        <code>composer install</code>

        <p>Agora você pode inserir o shortcode na página ou post desejado. Se preferir, insira o shortcode via código PHP.</p> 

        <code>[loterias loteria="lotofacil" concurso="ultimo"]</code> 

        <p>Para incluir o shortcode em um arquivo PHP, utilize a seguinte função nativa do WordPress:</p>

        <code>echo do_shortcode('[loterias loteria="lotofacil" concurso="ultimo"]');</code>

        <p>Substitua "lotofacil" pelo nome da loteria desejada. Abaixo estão todas as loterias compatíveis:</p>

        <ul class="list" style="list-style: disc; margin-left: 30px;">
            <li>maismilionaria</li>
            <li>megasena</li>
            <li>lotofacil</li>
            <li>quina</li>
            <li>lotomania</li>
            <li>timemania</li>
            <li>duplasena</li>
            <li>federal</li>
            <li>diadesorte</li>
            <li>supersete</li>
        </ul>

        <p>Você também pode substituir "ultimo" pelo número do concurso desejado.</p>

        <span class="line"></span>

        <h2>Instruções para o PHP CodeSniffer</h2>

        <p>Navegue até a plasta do plugin e digite o seguinte comando:</p>

        <code> vendor/bin/phpcs src/ </code>

        <span class="line"></span>
        
        <h2>Observações</h2>

        <p>O plugin possui as seguintes características:</p>

        <ol class="list">
            <li>Foi implementada uma regra para impedir a edição dos dados salvos no CPT (Custom Post Type) "loterias" pelo backend.</li>
            <li>Existe um aviso para evitar a criação direta dos CPT "loterias" pelo backend.</li>
            <li>Foram adicionadas colunas na página de listagem do CPT "loterias" para exibir dados importantes.</li>
            <li>O plugin conta com uma página de documentação.</li>
        </ol>

        <span class="line"></span>

        <h2>Testes de Shortcodes</h2>

        <p>Abaixo estão exemplos de shortcodes testados durante o desenvolvimento, demonstrando as possibilidades do plugin:</p>

        <h3>Shortcodes Funcionais</h3>

        <code>[loterias loteria="megasena"]</code>
        <code>[loterias loteria="megasena" concurso=""]</code>
        <code>[loterias loteria="megasena" concurso="ultimo"]</code>
        <code>[loterias loteria="megasena" concurso="2698"]</code>

        <h3>Shortcodes quebrados</h3>

        <code>[loterias loteria="megasena" concurso="opa"]</code>
        <code>[loterias loteria="bicho" concurso="teste"]</code>
        <code>[loterias loteria="tigrinho" concurso="teste"]</code>
        <code>[loterias loteria="" concurso="123teste"]</code>
        <code>[loterias concurso="teste"]</code>

    </div>
    <?php
    $object = ob_get_contents();
    /* Clean buffer */
    ob_end_clean();
    /* Return the content */
    return $object;
}