<?php

namespace loterias\classes;

class Loteria
{


    public $concurso;
    public $loteria;
    public $dados;



    /* public function AcessaApiXXXXXXXXXXXXXXXX()
    {
        $concursoInfo = "";
        //verificando se existe um  concurso "setado"
        if (!empty($this->concurso)) {
            $concursoInfo = "/" . $this->concurso;
        } else {
        }


        // URL da API que você deseja acessar
        $url = 'https://loteriascaixa-api.herokuapp.com/api/' . $this->loteria . '' . $concursoInfo;
 

        // Inicia uma nova sessão cURL
        $ch = curl_init();

        // Configura a URL para onde a requisição será enviada
        curl_setopt($ch, CURLOPT_URL, $url);

        // Configura cURL para retornar o resultado como uma string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Executa a requisição
        $response = curl_exec($ch);

        // Verifica se houve erros
        if (curl_errno($ch)) {
            echo 'Erro: ' . curl_error($ch);
        } else {
            // Converte a resposta JSON em um array associativo PHP
            $data = json_decode($response, true);
            $this->grid($data);
        }

        // Fecha a sessão cURL
        curl_close($ch);


        return $this;
    }*/




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
        } else {
        }




        $loteria = $this->loteria;
        $html = '<div id="resultados" class="' . $loteria . ' font">';
        $linha = 0;
        foreach ($data as $dd) :

            #---------------------------------------------------------------------------------------
            #--------Aqui eu vou verificar se alguns campos possuem ou não valores------------------
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
            #---------------------------------------------------------------------------------------
            #---------------------------------------------------------------------------------------

            if ($linha < 3) {
                $html .= "<h2> Concurso: " . $dd['concurso'] . " " . $dd['data'] . "</h2>";
                $html .= "<ul id='dezenas'>";
                foreach ($dd['dezenasOrdemSorteio'] as $d) :
                    $html .= "<li>" . $d . "</li>";
                endforeach;
                $html .= "</ul>";

                $html .= "<div> ";
                $html .= "</div>";
                $html .= "<h3><p>Premio: </p> <p>" . $dd['valorAcumuladoConcursoEspecial'] . "</p></h3>";


                $html .= "<table>";

                $html .= "<thead>";
                $html .= "<td>faixa: </td>";
                $html .= "<td>ganhadores: </td>";
                $html .= "<td>premio: </td>";
                $html .= "</thead>";

                foreach ($dd['premiacoes'] as $pp) {
                    $html .= "<tr>";
                    $html .= "<td>" . $pp['faixa'] . "</td>";
                    $html .= "<td>" . $pp['ganhadores'] . "</td> ";
                    $html .= "<td>" . $pp['valorPremio'] . " </td>";
                    $html .= "</tr>";
                }
                $html .= "</table>";



                //$html .= "<hr><div>";
                $linha++;
            }
        endforeach;
        $html .= "</div>";
        $this->dados = $html;
    }
}
