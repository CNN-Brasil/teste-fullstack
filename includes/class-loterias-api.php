<?php
class Loterias_API {
    private $api_url = 'https://loteriascaixa-api.herokuapp.com/api/';

    public function buscar_resultado($loteria, $concurso = 'ultimo') {
        if(!$concurso || $concurso == 'ultimo') {
            $concurso = 'latest';
        }
        // Implementação de cache
        $cache_key = "resultado_{$loteria}_{$concurso}";
        $cached_result = get_transient($cache_key);

        // Debug: Verificar se o cache está funcionando
        if ($cached_result) {
            error_log("Cache HIT: {$cache_key}");
            return $cached_result;
        } else {
            error_log("Cache MISS: {$cache_key}");
        }

        // Fazer a requisição HTTP
        $response = wp_remote_get("{$this->api_url}/{$loteria}/{$concurso}");

        // Debug: Verificar se a requisição falhou
        if (is_wp_error($response)) {
            error_log('Erro na requisição: ' . $response->get_error_message());
            return false;
        }

        // Debug: Verificar o status da resposta HTTP
        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code != 200) {
            error_log('Resposta com código HTTP inválido: ' . $status_code);
            return false;
        }

        // Obter response
        $data = wp_remote_retrieve_body($response);

        // Converter JSON em array PHP
        $decoded_data = json_decode($data, true);

        // Debug: Verificar se o JSON foi decodificado corretamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Erro ao decodificar JSON: ' . json_last_error_msg());
            return false;
        }

        // Armazenar no cache para futuras requisições
       set_transient($cache_key, $decoded_data, HOUR_IN_SECONDS); // Cache por 1 hora

        return $decoded_data;
    }
}
