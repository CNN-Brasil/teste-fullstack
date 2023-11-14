<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasReturnHtml - Responsável por gerar o HTML para exibição do resultado da loteria.
 */
class LoteriasReturnHtml {

    // Propriedades da classe
    protected string $html;
    protected array $day = ['1' => 'Segunda-Feira', '2' => 'Terça-Feira', '3' => 'Quarta-Feira', '4' => 'Quinta-Feira', '5' => 'Sexta-Feira', '6' => 'Sábado', '7' => 'Domingo'];
    protected int $premiacao = 0;

    /**
     * Gera o HTML para exibição do resultado da loteria.
     *
     * @param string $contentData Dados do resultado da loteria em formato JSON.
     * @return string HTML gerado.
     */
    public function html($contentData) {

        // Decodifica os dados JSON
        $contentData = json_decode($contentData);
        
        // Obtém o dia da semana do sorteio
        $explodeDate = explode('/', $contentData->data);
        $date = gmdate("N",strtotime($explodeDate[2].'-'.$explodeDate[1].'-'.$explodeDate[0]));

        // Calcula a premiação total se não houver acumulação
        if ($contentData->acumulou === false) {
            foreach($contentData->premiacoes as $key => $value) {
                $this->premiacao += $value->valorPremio * $value->ganhadores;   
            }
        } 

        // Gera o HTML
        $this->html = '<div class="loteria-concourse-wrapper"><div class="loteria-concourse '.$contentData->loteria.'">';
        $this->html .= '<div class="loteria-card">';
        
        $this->html .= '<div class="loteria-card-header">';
        $this->html .= '<h3>Concurso '.$contentData->concurso.' • '.$this->day[$date].' '.$contentData->data.'</h3>';
        $this->html .= '</div>';
        
        $this->html .= '<div class="loteria-card-container">';

        if (count($contentData->dezenasOrdemSorteio)) {
            $this->html .= '<ul class="loteria-card-number">';
            foreach ($contentData->dezenasOrdemSorteio as $key => $value) {
                $this->html .= '<li><span>'.$value.'</span></li>';
            }
            $this->html .= '</ul>';
        }

        $this->html .= '</div>';

        $this->html .= '<div class="loteria-card-prize-container"><div class="loteria-card-prize">';
        $this->html .= '<p>'.($contentData->acumulou === false ? "PRÊMIO" :  "ACUMULOU").'</p>';
        $this->html .= '</strong>R$ '.number_format($contentData->acumulou === false ? $this->premiacao : $contentData->valorEstimadoProximoConcurso, 2, ',', '.').'</strong>';
        $this->html .= '</div></div>';

        $this->html .= '<div class="loteria-card-table-container">';
        $this->html .= '<table class="loteria-card-table">';
        $this->html .= '<thead> <tr> <th>faixas</th> <th>ganhadores</th> <th>prêmio</th> </tr> </thead>';
        $this->html .= '<tbody>';
        foreach ($contentData->premiacoes as $key => $contentPremio) {
            $this->html .= '<tr><td>'.$contentPremio->descricao.'</td><td>'.$contentPremio->ganhadores.'</td><td>'.number_format($contentPremio->valorPremio, 2, ',', '.').'</td></tr>';
        }
        $this->html .= '</tbody>';
        $this->html .= '</table>';
        
        $this->html .= '</div>';

        $this->html .= '</div>';
        $this->html .= '</div></div>';

        // Retorna o HTML gerado
        return $this->html;
    }

}