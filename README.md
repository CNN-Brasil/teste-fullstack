## Desafio Fullstack - CNN Brasil - Realizado
### Instruções para utilização
Ao baixar o plugin, descompacte o mesmo e copie dentro da pasta de plugins do Wordpress.


Você pode inserir o shortcode na página, post e widget.

```bash
  [loterias loteria="megasena" concurso="2701"]
```

Código via arquivo php em uma views, por exemplo

```bash
 <?= do_shortcode('[loterias loteria="lotofacil" concurso="3057"]'); ?>
```

Utilize a loteria que quiser, basta alterar o valor do parâmetro "loteria". A seguir temos todas as loterias válidas:
- maismilionaria
- megasena
- lotofacil
- quina
- lotomania
- timemania
- duplasena
- federal
- diadesorte
- supersete

> [!TIP]
> O parâmetro "concurso" poderá ser em branco para sempre trazer o último.

