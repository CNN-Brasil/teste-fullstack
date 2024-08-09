<?php

namespace loterias\classes;

class Loteria
{


    public $concurso;
    public $loteria;
    public $dados;
    private $convert;
    /*****Aqui eu vou especificar se quero ou não  que converta alguma informação ******/




    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    public function AcessaApi()
    {
        $concursoInfo = "";
        if (!empty($this->concurso)) {
            $concursoInfo = "/" . $this->concurso;
        } 
        elseif ($this->concurso == "ultimo") { $concursoInfo = "/" . 'latest'; } 
        else { $concursoInfo = "/" . 'latest'; }

        $postTitle = $this->loteria . " - Concurso " . $this->concurso;







        /*************************************************************************** */
        /**Aqui eu vou ver primeiro, se o numero do concurso foi "setado"**/
        if (isset($this->concurso)):
            // informanndo que  eu quero que o conteudo deve ser convertido para array;
            $this->convert = 1;
            // Verifica se já existe um post com este título
            $existing_post_content = $this->get_existing_post_content($postTitle);
            if ($existing_post_content) {
                echo $existing_post_content;
                return $this;
            }
        endif;
        /************************************************************************** */




        #url que vai ser acessaada
        $url = 'https://loteriascaixa-api.herokuapp.com/api/' . $this->loteria . $concursoInfo;

        // Definindo o nome do arquivo de cache com base na URL
        $cacheDir = plugin_dir_path(__FILE__) . 'cache/';
        $cacheFile = $cacheDir . md5($url) . '.json';

        // Verifica se o diretório de cache existe, se não, cria com permissões
        if (!file_exists($cacheDir)) {
            if (!mkdir($cacheDir, 0755, true)) {
                error_log('Falha ao criar o diretório de cache: ' . $cacheDir);
                return;
            }
        }

        // Verifica se o cache existe e é recente (por exemplo, 1 hora)
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 3600)) {
            // Lê os dados do cache
            $data = json_decode(file_get_contents($cacheFile), true);
        } else {
            // Inicia uma nova sessão cURL
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Erro: ' . curl_error($ch);
            } else {
                $data = json_decode($response, true);

                // Salva a resposta em cache
                if (file_put_contents($cacheFile, $response) === false) {
                    error_log('Falha ao escrever no arquivo de cache: ' . $cacheFile);
                } else {
                    // Define as permissões do arquivo de cache para leitura e escrita (644)
                    chmod($cacheFile, 0644);
                }
            }

            curl_close($ch);
        }


        // Atualiza o conteúdo do post com a nova resposta da API
        $this->grid($data);

        return $this;
    }



    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/


    private function grid($data)
    {


        if (!isset($data[0])) {
            $dados = $data;
            $data = array($dados);
        }


        $loteria = $this->loteria;
        $html = '<div id="resultados" class="' . $loteria . ' font">';
        $linha = 0;

        foreach ($data as $dd) {

            if (!isset($dd['concurso'])) {
                $dd['concurso'] = "não informado";
            }
            if (!isset($dd['data'])) {
                $dd['data'] = "não informada";
            }
            if (!isset($dd['dezenasOrdemSorteio'])) {
                $dd['dezenasOrdemSorteio'] = array();
            }
            if (!isset($dd['valorAcumuladoConcursoEspecial'])) {
                $dd['valorAcumuladoConcursoEspecial'] = null;
            }
            if (!isset($dd['premiacoes'])) {
                $dd['premiacoes'] = array();
            }


            // Cria o título do post
            $post_title = $loteria;
            if ($dd['concurso'] !== "não informado") {
                $post_title .= " - Concurso " . $dd['concurso'];
            }

     
                // Se o post não existir, cria o novo conteúdo
                $html .= "<h2> Concurso: " . $dd['concurso'] . " " . $dd['data'] . "</h2>";
                $html .= "<ul id='dezenas'>";
                foreach ($dd['dezenasOrdemSorteio'] as $d) {
                    $html .= "<li>" . $d . "</li>";
                }
                $html .= "</ul>";

                $html .= "<div> ";
                $html .= "</div>";
                $html .= "<h3><p>Premio: </p> <p>" . $dd['valorAcumuladoConcursoEspecial'] . "</p></h3>";

                $html .= "<table>";
                $html .= "<thead>";
                $html .= "<td>Faixa: </td>";
                $html .= "<td>Ganhadores: </td>";
                $html .= "<td>Premio: </td>";
                $html .= "</thead>";

                foreach ($dd['premiacoes'] as $pp) {
                    $html .= "<tr>";
                    $html .= "<td>" . $pp['faixa'] . "</td>";
                    $html .= "<td>" . $pp['ganhadores'] . "</td>";
                    $html .= "<td>" . $pp['valorPremio'] . " </td>";
                    $html .= "</tr>";
                }
                $html .= "</table></div>";

                // Salva o novo post
                $this->save_to_custom_post($post_title, $html);
            
            $linha++;
        }
        $html .= "</div>";
        $this->dados = $html;
    }
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/

    private function get_existing_post_content($title)
    {
        // Verifica se o post já existe
        $existing_post = get_page_by_title($title, OBJECT, 'loterias');

        if ($existing_post) { // Verifica se o post foi encontrado
            // Se o post já existir e $this->convert for 1, retorna o conteúdo do post
            if ($this->convert == 1) {
                return $existing_post->post_content;
            }

            // Retorna o conteúdo do post
            return $existing_post->post_content;
        }

        // Retorna false se o post não existir
        return false;
    }

    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/






    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
    private function save_to_custom_post($title, $content)
    {


        // Verifica se o post já existe
        $existing_post = get_page_by_title($title, OBJECT, 'loterias');
        if ($existing_post) {
            // Se o post já existir, apenas atualiza o conteúdo
            $post_id = $existing_post->ID;
            $post_data = array(
                'ID'           => $post_id,
                'post_content' => $content,
            );
            wp_update_post($post_data);
        } else {
            // Se o post não existir, cria um novo
            $post_data = array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'loterias',
            );
            $post_id = wp_insert_post($post_data);
        }
    }

    /*************************************************************************************************/
    /*************************************************************************************************/
    /*************************************************************************************************/
}
