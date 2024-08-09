<h1 align="center">Olá 👋, eu sou Ramon Mendes - Desenvolvedor de Software</h1>
<h3 align="center">Um desenvolvedor back-end apaixonado por tecnologia</h3>

<h1 align="center">Bem-vindo ao CNN Brasil Loterias</h1>

<h2 align="center">Plugin WordPress para Resultados de Loteria</h2>

<p align="center">
  <img src="https://placeholder-for-cnn-brasil-loterias-logo.com/logo.png" alt="logo-cnn-brasil-loterias" />
</p>

<h3 align="center">Um plugin WordPress para exibir resultados de loterias da Caixa</h3>

<p align="center">
  🔭 Este projeto é um <a href="https://github.com/RamonSouzaDev/cnn-brasil-loterias">plugin WordPress para exibir resultados de loterias</a><br>
  🌱 Desenvolvido com <strong>WordPress, PHP, Docker, e Redis</strong><br>
  📫 Como entrar em contato: <strong>dwmom@hotmail.com</strong>
</p>

<h2>Tutorial em Vídeo</h2>

<p>Para uma demonstração visual de como instalar e usar o plugin, assista ao nosso tutorial em vídeo:</p>

<p align="center">
  <a href="https://youtu.be/6Hm5ZW7_zHY" target="_blank">
    <img src="https://img.youtube.com/vi/6Hm5ZW7_zHY/0.jpg" alt="Tutorial em Vídeo do CNN Brasil Loterias" />
  </a>
</p>

<p align="center">
  <a href="https://youtu.be/6Hm5ZW7_zHY" target="_blank">Clique aqui para assistir ao tutorial</a>
</p>

<h3 align="left">Conecte-se comigo:</h3>
<p align="left">
  <a href="https://linkedin.com/in/ramon-mendes-b44456164/" target="blank">
    <img align="center" src="https://raw.githubusercontent.com/rahuldkjain/github-profile-readme-generator/master/src/images/icons/Social/linked-in-alt.svg" alt="ramon-mendes-b44456164/" height="30" width="40" />
  </a>
</p>

<h3 align="left">Linguagens e Ferramentas:</h3>
<p align="left"> 
  <a href="https://www.php.net" target="_blank" rel="noreferrer"> 
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="php" width="40" height="40"/>
  </a>
  <a href="https://wordpress.org/" target="_blank" rel="noreferrer"> 
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/wordpress/wordpress-plain.svg" alt="wordpress" width="40" height="40"/>
  </a>
  <a href="https://www.docker.com/" target="_blank" rel="noreferrer"> 
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/docker/docker-original-wordmark.svg" alt="docker" width="40" height="40"/>
  </a>
  <a href="https://redis.io" target="_blank" rel="noreferrer"> 
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/redis/redis-original-wordmark.svg" alt="redis" width="40" height="40"/>
  </a>
</p>

<h2>Instalação</h2>

<ol>
  <li>Clone o repositório:
    <pre><code>git clone https://github.com/RamonSouzaDev/cnn-brasil-loterias.git</code></pre>
  </li>
  <li>Entre na pasta do projeto:
    <pre><code>cd cnn-brasil-loterias</code></pre>
  </li>
  <li>Execute o ambiente Docker:
    <pre><code>docker-compose up --build</code></pre>
  </li>
  <li>Acesse o WordPress em <code>http://localhost:8080</code> e ative o plugin CNN Brasil Loterias no painel de administração.</li>
</ol>

<h2>Uso</h2>

<p>Use o shortcode <code>[loterias]</code> para exibir os resultados da loteria em qualquer página ou post.</p>

<p>Exemplos:</p>
<ul>
  <li><code>[loterias loteria="megasena"]</code></li>
  <li><code>[loterias loteria="lotofacil" concurso="ultimo"]</code></li>
  <li><code>[loterias loteria="quina" concurso="5821" debug="true"]</code></li>
</ul>

<h3>Parâmetros do Shortcode</h3>
<table>
  <tr>
    <th>Parâmetro</th>
    <th>Descrição</th>
    <th>Valores Possíveis</th>
  </tr>
  <tr>
    <td><code>loteria</code></td>
    <td>Tipo de loteria</td>
    <td>megasena, lotofacil, quina, etc.</td>
  </tr>
  <tr>
    <td><code>concurso</code></td>
    <td>Número do concurso (opcional)</td>
    <td>Número específico ou "ultimo"</td>
  </tr>
  <tr>
    <td><code>debug</code></td>
    <td>Modo de depuração (opcional)</td>
    <td>true ou false</td>
  </tr>
</table>

<h2>Desenvolvimento</h2>

<h3>Estrutura do Plugin</h3>

<p>O plugin é composto pelas seguintes classes principais:</p>

<table>
  <tr>
    <th>Classe</th>
    <th>Descrição</th>
  </tr>
  <tr>
    <td><code>CNN_Brasil_Loterias</code></td>
    <td>Classe principal do plugin</td>
  </tr>
  <tr>
    <td><code>CNN_Loterias_API</code></td>
    <td>Gerencia as interações com a API de loterias</td>
  </tr>
  <tr>
    <td><code>CNN_Loterias_Shortcode</code></td>
    <td>Processa e exibe o shortcode de resultados</td>
  </tr>
  <tr>
    <td><code>Redis_Client</code></td>
    <td>Gerencia o cache usando Redis</td>
  </tr>
</table>

<h2>Contribuindo</h2>

<p>Contribuições são bem-vindas! Por favor, leia o arquivo CONTRIBUTING.md para detalhes sobre nosso código de conduta e o processo para enviar pull requests.</p>

<h2>Licença</h2>

<p>Este projeto está licenciado sob a Licença MIT - veja o arquivo <a href="LICENSE.md">LICENSE.md</a> para detalhes.</p>

<h2>Contato</h2>

<p>
  Ramon Mendes - <a href="https://twitter.com/RamonMendesDev">@RamonMendesDev</a> - dwmom@hotmail.com
</p>

<p>
  Link do Projeto: <a href="https://github.com/RamonSouzaDev/cnn-brasil-loterias">https://github.com/RamonSouzaDev/cnn-brasil-loterias</a>
</p>