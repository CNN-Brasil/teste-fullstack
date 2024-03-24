## Desafio Fullstack - CNN Brasil - Estevão Acioli
### Instruções para utilização
Baixe o plugin, descompacte-o e copie para dentro da pasta plugin de seu site Wordpress.

A pasta vendor foi adicionada ao gitignore, portanto, é necessário rodar o composer install.
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

Substitua "lotofacil" pelo nome da loteria desejada. Abaixo estão todas as loterias compatíveis:

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

Você também pode substituir "ultimo" pelo número do concurso desejado.

### Instruções para o PHP CodeSniffer

Navegue até a plasta do plugin e digite o seguinte comando:

```bash
 vendor/bin/phpcs src/
```
### Observações

O plugin possui as seguintes características:

- Foi implementada uma regra para impedir a edição dos dados salvos no CPT (Custom Post Type) "loterias" pelo backend.
- Existe um aviso para evitar a criação direta dos CPT "loterias" pelo backend.
- Foram adicionadas colunas na página de listagem do CPT "loterias" para exibir dados importantes.
- O plugin conta com uma página de documentação.

### Testes de Shortcodes

Abaixo estão exemplos de shortcodes testados durante o desenvolvimento, demonstrando as possibilidades do plugin:

- Shortcodes Funcionais :tada:

```bash
  [loterias loteria="megasena"]
```
```bash
  [loterias loteria="megasena" concurso=""]
```
```bash
  [loterias loteria="megasena" concurso="ultimo"]
```
```bash
  [loterias loteria="megasena" concurso="2698"]
```

- Shortcodes quebrados :fire:

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
  [loterias loteria="" concurso="123teste"]
```
```bash
  [loterias concurso="teste"]
```


Se tiver alguma dúvida ou solicitação por favor me avise pelo e-mail: estevaoaciolice@gmail.com ou pelo WhatsApp (85)98552-9837.
