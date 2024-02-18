## Desafio Fullstack - CNN Brasil

O teste consiste em criar um plugin WordPress com um shortcode que exibirá os resultados dos jogos das Loterias Caixa.

### Índice

1. [Funcionamento do plugin](#funcionamento-do-plugin)
2. [Instruções para o teste](#instruções-para-o-teste)
3. [Requisitos](#requisitos)

#### Funcionamento do plugin

- Ao ativar o plugin, ele já deve estar funcional, não deve ser necessário nenhum tipo de configuração ou ajuste prévio;
- Deve ser criado um post-type chamado "Loterias", em que os resultados já buscados deverão ser salvos.
- O shortcode deve poder ser utilizado em qualquer tema WordPress, em qualquer página ou post, inclusive via back-end com PHP;
- Caso o parâmetro "concurso" do shortcode seja o número de um concurso e não "ultimo", deve-se primeiro verificar se o concurso já está cadastrado no post-type "Loterias", e seja consultada a API de loterias apenas caso o concurso não esteja registrado no post-type;
- Caso o parâmetro "concurso" do shortcode seja "ultimo", deve-se acessar diretamente a API, e após isso, verificar se o concurso já está registrado no post-type, e caso não esteja, cadastrar ele;
- Após fazer as buscas pelo concurso, o resultado será exibido no front-end com um layout personalizado.

#### Instruções para o teste

A avaliação do teste será feita aqui no GitHub, então não se esqueça de seguir esses passos:

- Faça um fork deste repositório;
- Desenvolva todo o projeto na branch master;
- Após finalizado, abra uma PR com o código para este repositório;

A API que deve ser consultada é a seguinte:

- https://github.com/guto-alves/loterias-api

O layout do Figma está no seguinte arquivo:

- https://www.figma.com/file/F7T7TCcoObWXdjUyngIENl/Desafio-Fullstack---CNN-Brasil

O repositório utiliza composer para validação do código. Você está livre para utilizar as ferramentas do composer, como autoload caso queira, porém, não utilize outras dependências do PHP.

#### Requisitos

- O plugin deve ser compatível com a versão mais recente do WordPress;
- Deve-se usar preferencialmente funções e hooks nativos do WordPress ao invés de funções nativas do PHP;
- Todo tipo de query ou chamada de API deve ser cacheada;
- O código deve seguir os padrões estabelecidos pelo WordPress Coding Standards e WP VIP Coding Standards;
- O código deve ser orientado a objetos;
- O código deve ser validado utilizando PHPCS;
- O layout do front-end do shortcode deve seguir o que foi apresentado no Figma;
- Todo o código do projeto deve estar em um único plugin.

O shortcode deverá aceitar os seguintes parâmetros:

- loteria: O nome da loteria, por exemplo, "megasena"
- concurso: O número do concurso da loteria, podendo ser um número ou "ultimo", caso não seja preenchido, considerar sempre "ultimo" como padrão
