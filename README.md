## Desafio Prático para Desenvolvedores Full-Stack com foco em WordPress e Next.js

Seu objetivo é construir uma solução Full-Stack que integre WordPress como backend e Next.js como frontend, focando na gestão e exibição de dados de produtos.

### Funcionalidades Essenciais (WordPress Plugin)

O teste consiste no desenvolvimento de um plugin para WordPress com as seguintes funcionalidades:

1. **Consumo de API Externa:**

   * O plugin deve ser capaz de consumir uma API externa de produtos.

   * A API está localizada em https://restful-api.dev/

   * **Exemplo de Estrutura de Dados:**

     ```
     [
        {
           "id": "2",
           "name": "Apple iPhone 12 Mini, 256GB, Blue",
           "data": null
        },
        {
           "id": "3",
           "name": "Apple iPhone 12 Pro Max",
           "data": {
              "color": "Cloudy White",
              "capacity GB": 512
           }
        },
        {
           "id": "4",
           "name": "Apple iPhone 11, 64GB",
           "data": {
              "price": 389.99,
              "color": "Purple"
           }
        }
     ]
     
     
     ```

2. **Sistema de Cache:**

   * Os dados consumidos da API de produtos devem ser salvos **somente** em cache no WordPress.

   * Junto aos dados, deve ser armazenada a data e hora exatas de quando a consulta à API externa foi realizada.

3. **Comando WP-CLI para Limpeza de Cache:**

   * Implemente um comando WP-CLI que permita limpar os dados do cache manualmente.

   * Exemplo de uso esperado: `wp meuplugin cache clear`.

### Funcionalidades Essenciais (WordPress Admin)

1. **Página no WP Admin:**

   * Crie um novo item de menu no painel administrativo do WordPress (no menu lateral).

   * Esse item do menu deve possuir duas páginas.

   * Uma página deve exibir uma lista dos produtos salvos em cache.

     * A listagem deve utilizar o sistema de tabela nativo do WordPress (`WP_List_Table`) para exibir os dados de forma organizada.

   * A outra deve ser usada para controle de cache.

     * Nessa página deve-se mostrar a data e hora da última vez que o cache foi salvo, e também deve-se ter um botão para fazer a limpeza manual do cache

   * **Comportamento do Cache:** Caso não haja dados no cache (ou o cache esteja expirado), a consulta à API externa deve ser feita novamente, e os novos dados devem ser salvos em cache com a nova data/hora da consulta.

### Funcionalidades Essenciais (WordPress API)

1. **API de Listagem de Produtos em Cache:**

   * Crie um endpoint de API REST no WordPress que retorne todos os dados de produtos salvos em cache.

   * **Comportamento do Cache:** Se não houver dados no cache (ou o cache estiver expirado), a API deve realizar a consulta à API externa, salvar os dados e a data/hora no cache, e então retornar esses dados.

   * Exemplo de endpoint: `/wp-json/meuplugin/v1/products`

2. **API de Detalhe de Produto em Cache:**

   * Crie um segundo endpoint de API REST no WordPress que retorne os detalhes de um único produto, baseado em seu ID. Este endpoint deve consultar os dados *do cache*.

   * **Comportamento do Cache:** Se não houver dados no cache (ou o cache estiver expirado), a API deve realizar a consulta à API externa, salvar os dados e a data/hora no cache, e então retornar os detalhes do produto solicitado.

   * Exemplo de endpoint: `/wp-json/meuplugin/v1/products/{id}`

### Criação das Páginas (Next.js)

No lado do Next.js, você deve criar duas páginas que irão consumir as APIs criadas no WordPress e renderizar as informações:

1. **Página de Listagem de Produtos:**

   * Esta página deve consultar o endpoint de listagem de produtos do WordPress.

   * Deve exibir uma lista simples de produtos, mostrando somente o `id` e o `name` de cada produto.

   * Esta será a página inicial da aplicação

2. **Página de Detalhe de Produto:**

   * Esta página deve consultar o endpoint de detalhe de produto do WordPress, usando o ID do produto como parâmetro.

   * Deve exibir detalhes mais completos do produto (todos os itens que estejam dentro de `data`, caso existam).

   * Exemplo de rota: `/products/[id]`

O layout da aplicação pode ser feito seguindo o arquivo do Figma: https://www.figma.com/design/k3hZhOFMxzQFrCpP91eEpf/Desafio-Fulll-stack

### Estratégias de Renderização (Next.js)

* As páginas do Next.js devem ser renderizadas utilizando **SSR (Server-Side Rendering)**, **SSG (Static Site Generation)** ou **ISR (Incremental Static Regeneration)**.

* Justifique suas escolhas de renderização no `README.md`, explicando por que cada estratégia foi utilizada para cada página.

### O que esperamos ver em seu repositório (Entrega no GitHub)

Você deverá criar um **repositório privado no GitHub** e conceder acesso ao usuário que foi enviado a você.

Neste repositório, esperamos encontrar a seguinte estrutura e conteúdo:

1. **Código-fonte do plugin (somente o código do plugin, não incluindo coisas do WordPress):**

   * Implementação das funcionalidades do plugin (WP Admin, APIs REST, WP-CLI e Cache).

   * **Estrutura de Projeto:** Organização lógica do código do plugin (ex: arquivos de classes, métodos, etc.).

   * **Boas Práticas:** Demonstração de código limpo, reusabilidade, legibilidade e manutenibilidade (seguindo padrões como PSR, DDD, Clean Code e WPCS).

   * **Tratamento de Erros:** Tratamento de erros mínimo e mensagens claras.

2. **Código-fonte da Aplicação Next.js:**

   * Implementação das páginas de listagem e detalhe de produto.

   * **Estrutura de Projeto:** Organização lógica do código (ex: componentes, serviços, rotas e lógica bem definidas).

   * **Boas Práticas:** Demonstração de código limpo, reusabilidade, legibilidade e manutenibilidade (seguindo padrões como DDD, Clean Code).

   * **Trat
