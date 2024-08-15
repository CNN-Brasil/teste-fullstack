<?php
if (!defined('ABSPATH')) {
    exit();
}
function lotcaixa_documentation_page_content(){   
    ?>
    <div id="page-documentation" class="container">
        <h1>#Documentação - Version: 1.0</h1>

        <h2> Instruções para utilização</h2>

        <p>para o funcionamento do plugin é necessário rodar o composer novamente</p>

        <p>Navegue até a pasta do plugin, e digite o seguinte comando:</p>

        <code>composer install</code>

        <p>Para incluir o shortcode em um arquivo PHP, utilize a seguinte função nativa do WordPress:</p> 

        <code>[loterias loteria="lotofacil" concurso="ultimo"]</code> 

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
    </div>
    <?php
   
}