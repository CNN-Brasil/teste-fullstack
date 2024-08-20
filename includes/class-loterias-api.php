<?php

class Loterias_API {
    public static function get_results($loteria, $concurso) {
        $url = "https://loteriascaixa-api.herokuapp.com/api/$loteria/$concurso";
        $response = wp_remote_get($url);
        var_dump($response);
        if (is_wp_error($response)) {
            return null;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
