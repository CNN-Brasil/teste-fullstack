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

        // Fazer a requisição HTTP
        $response = wp_remote_get("{$this->api_url}/{$loteria}/{$concurso}");

        // Obter response
        $data = wp_remote_retrieve_body($response);

        // Converter JSON em array PHP
        $decoded_data = json_decode($data, true);

        // Armazenar no cache para futuras requisições
       set_transient($cache_key, $decoded_data, HOUR_IN_SECONDS); // Cache por 1 hora

        return $decoded_data;
    }
}
