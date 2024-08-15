## Desafio - CNN Brasil - Guilherme Moreno
### Instruções para utilização
descompacte e copie para dentro da pasta plugin de seu site Wordpress.

para o funcionamento do plugin é necessário rodar o composer novamente
Navegue até a pasta do plugin, e digite o seguinte comando:

```bash
  composer install
```

Utilize o shortcode na página ou post desejado. Se preferir, insira o shortcode via código PHP.

```bash
  [loterias loteria="lotofacil" concurso="ultimo"]
```
Para incluir o shortcode em um arquivo PHP, utilize a seguinte função nativa do WordPress:

```bash
 <?php echo do_shortcode('[loterias loteria="lotofacil" concurso="ultimo"]'); ?>
```

Altere o valor de loteria para mudar a loteria desejada. Lista de loterias abaixo:

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

Também é possivel selecionar o jogo alterando o valor de concurso.

### Para o PHP CodeSniffer

Navegue até a plasta do plugin e digite o seguinte comando:

```bash
 vendor/bin/phpcs src/
```

### Testes de Shortcodes

Abaixo estão exemplos de shortcodes testados durante o desenvolvimento, demonstrando as possibilidades do plugin:

- Shortcodes Funcionais :tada:


```bash
  [loterias loteria="megasena" concurso=""]
```

```bash
  [loterias loteria="megasena"]
```

```bash
  [loterias loteria="megasena" concurso="1350"]
```

```bash
  [loterias loteria="megasena" concurso="ultimo"]
```


- Shortcodes quebrados :fire:

```bash
  [loterias loteria="megasena" concurso="opa"]
```
```bash
  [loterias loteria="bicho" concurso="primeiro"]
```
```bash
  [loterias loteria="tiger" concurso="teste"]
```
```bash
  [loterias loteria="" concurso="2004"]
```
```bash
  [loterias concurso="teste"]
```
