<?php

defined( constant_name:'ABSPATH') || exit;

class CreateHtml
{
    //protected function __construct() {}

    public function gerar_html_loteria($json) {
        // Decodificar o JSON para um array associativo
        //$dados_loteria = json_decode($json, true);
        $dados_loteria = $json;
        if ($dados_loteria === null) {
            // Tratar erro na decodificação do JSON, se necessário
            return '';
        }

        // Extrair informações relevantes do array
        $concurso = $dados_loteria['concurso'];
        $data = $dados_loteria['data'];
        $dia_semana = $this->obter_dia_semana($data);
        $numeros_sorteio = $dados_loteria['dezenas'];
        $premiacoes = $dados_loteria['premiacoes'];
        $loteria = $dados_loteria['loteria'];

        // Iniciar a construção do HTML
        $html = '<style>.txt-inter,.txt-topo{font-family:Inter}.f-20,.txt-topo{font-size:20px}.ct-div-block{display:flex;flex-wrap:wrap;flex-direction:column;align-items:flex-start}.ct-text-block{max-width:100%}.topo-loteria{width:100%;padding-top:20px;padding-bottom:20px}.center-h.ct-section .ct-section-inner-wrap,.center-h.oxy-easy-posts .oxy-posts,.center-h:not(.ct-section):not(.oxy-easy-posts),.topo-loteria.ct-section .ct-section-inner-wrap,.topo-loteria:not(.ct-section):not(.oxy-easy-posts){display:flex;flex-direction:row;justify-content:center}.txt-topo{font-weight:400;color:#fff;line-height:1.1}.numero-sorteado,.txt-premio{font-weight:800;line-height:1}.numeros-sorteados{padding-top:42px;padding-bottom:42px;gap:32px}.numeros-sorteados.ct-section .ct-section-inner-wrap,.numeros-sorteados:not(.ct-section):not(.oxy-easy-posts){gap:32px}.col-12{width:100%}.numero-sorteado{font-size:20px;border-radius:50%;width:67px;height:67px;text-align:center;margin:0}.numero-sorteado-federal{font-size:20px;border-radius:10px;height:50px;text-align:center;margin:0;padding:0 20px 0 20px;}.bg-megasena{background-color:#2d976a}.bg-quina{background-color:#261383}.bg-lotofacil{background-color:#921788}.bg-lotomania{background-color:#f58123}.bg-timemania{background-color:#3daf3e}.bg-duplasena{background-color:#a41628}.bg-federal{background-color:#133497}.bg-diadesorte{background-color:#ca8536}.bg-supersete{background-color:#a9cf50}.center-v.ct-section .ct-section-inner-wrap,.center-v.oxy-easy-posts .oxy-posts,.center-v:not(.ct-section):not(.oxy-easy-posts){display:flex;flex-direction:column;align-items:center}.txt-upper{text-transform:uppercase}.row-resultado{padding-top:42px;padding-bottom:42px;gap:15px}.base-loteria .ct-section-inner-wrap{padding:20px 0}.col-4{width:33.33%}.txt-megasena{color:#2d976a}.txt-quina{color:#261383}.txt-lotofacil{color:#921788}.txt-lotomania{color:#f58123}.txt-timemania{color:#3daf3e}.txt-duplasena{color:#a41628}.txt-federal{color:#133497}.txt-diadesorte{color:#ca8536}.txt-supersete{color:#a9cf50}.txt-white{color:#fff}.txt-black{color:#000}.row-txt{padding-top:15px;padding-bottom:15px}.row-txt.ct-section .ct-section-inner-wrap,.row-txt:not(.ct-section){display:flex;flex-direction:row}.pt-77{padding-top:62px}.txt-info{font-weight:500;line-height:1}.br-t{border-top:.1em solid #cbcbcb}.br-lr{border-right:.1em solid #cbcbcb;border-left:.1em solid #cbcbcb}.br-b{border-bottom:.1em solid #cbcbcb}</style>';
        $html .= '<section class="base-loteria">';
        $html .= '<div class="ct-section-inner-wrap">';
        // ... (continuar com a construção do HTML conforme necessário)

        // Adicionar informações do concurso e data
        $html .= '<div class="ct-div-block topo-loteria bg-' . $loteria . ' br-t br-lr">';
        $html .= '<div class="ct-text-block txt-topo txt-white">Concurso ' . $concurso . ' - ' . $dia_semana . ' ' . $data . '</div>';
        $html .= '</div>';

        // Adicionar números sorteados
        $html .= '<div class="ct-div-block numeros-sorteados col-12 center-h br-lr br-b">';
        foreach ($numeros_sorteio as $numero) {
            $html .= '<div class="ct-div-block center-h center-v numero-sorteado '. ($loteria === 'federal' ? 'numero-sorteado-federal' : '') . ' bg-' . $loteria . '"><p class="ct-text-block txt-inter txt-white">' . $numero . '</p></div>';
        }
        $html .= '</div>';

        // Adicionar informações de premiações
        $html .= '<div class="ct-div-block col-12 center-v txt-inter row-colunas pt-77 br-lr">';
        $html .= '<div class="ct-div-block col-12 row-txt br-b">';
        $html .= '<div class="ct-div-block col-4 center-h">';
        $html .= '<div class="ct-text-block f-20 txt-premio txt-' . $loteria . ' txt-inter">Faixas</div>';
        $html .= '</div>';
        $html .= '<div class="ct-div-block col-4 center-h">';
        $html .= '<div class="ct-text-block f-20 txt-premio txt-' . $loteria . ' txt-inter">Ganhadores</div>';
        $html .= '</div>';
        $html .= '<div class="ct-div-block col-4 center-h">';
        $html .= '<div class="ct-text-block f-20 txt-premio txt-' . $loteria . ' txt-inter">Prêmio</div>';
        $html .= '</div>';
        $html .= '</div>';

        foreach ($premiacoes as $premiacao) {
            $html .= '<div class="ct-div-block col-12 row-txt br-b">';
            $html .= '<div class="ct-div-block col-4 center-h">';
            $html .= '<div class="ct-text-block f-20 txt-inter txt-black txt-info">' . $premiacao['descricao'] . '</div>';
            $html .= '</div>';
            $html .= '<div class="ct-div-block col-4 center-h">';
            $html .= '<div class="ct-text-block f-20 txt-inter txt-black txt-info">' . $premiacao['ganhadores'] . '<br></div>';
            $html .= '</div>';
            $html .= '<div class="ct-div-block col-4 center-h">';
            $html .= '<div class="ct-text-block f-20 txt-inter txt-black txt-info">R$ ' . number_format($premiacao['valorPremio'], 2, ',', '.') . '<br></div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        // Fechar as tags finais
        $html .= '</section>';

        return $html;
    }

    private function obter_dia_semana($data){
        $semana = array(
            '1' => 'Segunda-Feira',
            '2' => 'Terça-Feira',
            '3' => 'Quarta-Feira',
            '4' => 'Quinta-Feira',
            '5' => 'Sexta-Feira',
            '6' => 'Sábado',
            '7' => 'Domingo'
        );

        // Converter a data para o formato yyyy-mm-dd
        $data_objeto = DateTime::createFromFormat('d/m/Y', $data)->format('Y-m-d');

        // Obter o número do dia da semana (1 = segunda, 7 = Domingo)
        $numero_dia_semana = date('N', strtotime($data_objeto));

        return $semana[$numero_dia_semana];


    }
}
