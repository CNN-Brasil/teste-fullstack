## Desafio Fullstack - CNN Brasil - Realizado
### Instruções para utilização
Baixe e instale o plugin e insira o shortcode em sua página ou post do Wordpress.

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

```bash
  [loterias loteria="lotomania" concurso="ultimo"]
```
Para incluir o shortcode em um arquivo php, use o comando abaixo.

```bash
 <?php echo do_shortcode('[loterias loteria="lotomania" concurso="ultimo"]'); ?>
```

### Instruções para o PHP CodeSniffer

Navegue até a plasta do plugin e digite o seguinte comando:

```bash
 vendor/bin/phpcs src/
```
### Observações

Ao desativar o plugin todos os cpts serão excluídos, desta maneira vamos evitar de deisar "sujeira" no banco.

Como é um CPT ele gera no menu Loterias os posts, não deve ser editado, pois é um resulado da loteria e não pode ser modificado. :)

Tentei codificar em português para facilitar o entendimento do meu código.

Se tiver alguma dúvida ou solicitação por favor me avise pelo e-mail: aldojuneo@gmail.com ou pelo WhatsApp (11)97520-9959.
