## Desafio Fullstack - CNN Brasil - Estevão Acioli
### Instruções para utilização
Baixe o plugin, descompacte-o e copie para dentro da pasta plugin de seu site Wordpress.

A pasta vendor foi adicionada ao gitignore, portanto, é necessário rodar o composer install.
Navegue até a pasta do plugin, e digite o seguinte comando:

```bash
  composer install
```

Agora basta inserir o shortcode na página ou post. Se preferir insira via código php.

```bash
  [loterias loteria="lotomania" concurso="ultimo"]
```
Para incluir o shortcode em um arquivo php, use o comando abaixo. Com esta função nativa do WordPress vocêce consegue executar um shortcode diretamente no seu PHP

```bash
 <?php echo do_shortcode('[loterias loteria="lotomania" concurso="ultimo"]'); ?>
```

Você pode substituir para qual loteria quiser, basta alterar o valor de loteria="nome_da_loteria" a seguir temos todas as loterias compatíveis: 
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

Você pode substituir para o concurso que quiser, basta alerar concurso="ultimo" para concurso="numero_do_concurso".

### Instruções para o PHP CodeSniffer

Navegue até a plasta do plugin e digite o seguinte comando:

```bash
 vendor/bin/phpcs src/
```
### Observações

Criei uma regra para impedir que os dados salvos no CPT loterias sejam editados no backend.
Também existe um aviso para que os CPT loterias nao sejam criados diretamente pelo backend, isto não impedi mas pelo menos avisa ao usuário para não fazer isto.
Foram acrescentadas colunas na pagina de listagem do CPT loterias para exibir dados importantes
O plugin também conta com uma página de documentação.

### Shotcodes & Teste

Abaixo listo alguns exemplos de shortcodes que foram testados durante o desenvolvimento, assim você pode ter ideia de todas a spossibilidades que o plugin suporta

:::success
Shortcodes funcionais :tada:
:::

```bash
  [loterias loteria="megasena"]
```
```bash
  [loterias loteria="megasena" concurso=""]
```
```bash
  [loterias loteria="megasena" concurso="ultimo"]
```

:::danger
Shortcodes quebrados :fire:
:::

```bash
  [loterias loteria="megasena" concurso="opa"]
```
```bash
  [loterias loteria="bicho" concurso="teste"]
```
```bash
  [loterias loteria="tigrinho" concurso="teste"]
```
```bash
  [loterias loteria="" concurso="teste"]
```
```bash
  [loterias concurso="teste"]
```


Se tiver alguma dúvida ou solicitação por favor me avise pelo e-mail: estevaoaciolice@gmail.com ou pelo WhatsApp (85)98552-9837.
