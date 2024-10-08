<?php
class Loterias_API {
    private $api_url = 'https://loteriascaixa-api.herokuapp.com/api/';

    public function buscar_resultado($loteria, $concurso = 'ultimo') {
        

        // Fazer a requisição HTTP
        $response = wp_remote_get("{$this->api_url}/{$loteria}/{$concurso}");

        // Debug: Verificar se a requisição falhou
        if (is_wp_error($response)) {
            error_log('Erro na requisição: ' . $response->get_error_message());
            return false;
        }

        // Obter response
        $data = wp_remote_retrieve_body($response);

        // Debug: Verificar response
        if (empty($data)) {
            error_log('Erro: Resposta vazia da API');
            return false;
        }

        // Converter JSON em array PHP
        $decoded_data = json_decode($data, true);

        return $decoded_data;
    }
}
