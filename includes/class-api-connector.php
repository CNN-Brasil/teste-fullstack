<?php
class API_Connector {
    const BASE_URL = 'https://loteriascaixa-api.herokuapp.com/api';

    public function get_results($loteria, $concurso = 'latest') {
        $url = self::BASE_URL . "/{$loteria}/{$concurso}";
        $response = wp_remote_get($url);
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return false;
        }
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}