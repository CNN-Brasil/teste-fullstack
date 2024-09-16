
# Plugin Resultados Loterias Caixa

Este plugin exibe os resultados das Loterias Caixa (Mega Sena, Quina, Loto Fácil, etc.) em qualquer página ou post do WordPress usando um shortcode. Os resultados são buscados via API e armazenados no post-type personalizado "Loterias" para evitar requisições repetidas.

## Funcionalidades

- Exibição de resultados da API das Loterias Caixa.
- Suporte a múltiplos concursos, incluindo o último concurso ou concursos específicos.
- Armazenamento de resultados em um post-type personalizado para evitar múltiplas chamadas à API.
- Layout dinâmico e responsivo com cores diferenciadas para cada tipo de loteria.

## Requisitos

- WordPress 5.0 ou superior.
- PHP 7.0 ou superior.

## Instalação

1. **Baixe o Plugin**: Faça o download dos arquivos do plugin.

2. **Upload Manual via FTP**:
   - Extraia o arquivo ZIP do plugin.
   - Envie a pasta extraída para o diretório `wp-content/plugins/` do seu WordPress.

3. **Ativação**:
   - No painel do WordPress, vá para `Plugins -> Plugins Instalados`.
   - Procure por "Resultados Loterias Caixa" e clique em **Ativar**.

## Uso do Shortcode

O plugin utiliza o shortcode `[loteria_resultado]` para exibir os resultados das Loterias Caixa.

### Parâmetros do Shortcode:

- `loteria`: Este parâmetro aceita o tipo do concurso que deseja exibir. **Ex: megasena, lotofacil etc..**.
- `concurso`: Este parâmetro aceita o número do concurso que deseja exibir. Se omitir o número, o plugin buscará o **último concurso disponível**.

#### Exemplos de Uso:

1. **Exibir o último concurso**:
   
   Use o shortcode sem parâmetros para exibir o último concurso disponível:
   ``` 
   [loteria_resultado loteria="megasena" concurso="ultimo"]
   ```

2. **Exibir um concurso específico**:
   
   Para exibir os resultados de um concurso específico, passe o número do concurso como parâmetro:
   ```
   [loteria_resultado loteria="megasena" concurso="1234"]
   ```

### Exibição no Frontend

Os resultados são exibidos em um layout flexível e responsivo, com cores associadas a cada tipo de loteria (Mega Sena, Quina, etc.). Você pode usar o shortcode em qualquer página, post ou widget no seu site WordPress.

## Desenvolvimento
  
### Arquivos Principais:

- `index.php`: Arquivo principal do plugin que registra o post-type, cria o shortcode e faz as requisições à API.
- `style.css`: Arquivo de estilo para customizar o layout dos resultados.
