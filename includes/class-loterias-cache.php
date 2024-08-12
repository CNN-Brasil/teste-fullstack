<?php

class Loterias_Cache {
    public static function get_results($loteria, $concurso) {
        $key = "loterias_{$loteria}_{$concurso}";
        return get_transient($key);
    }

    public static function set_results($loteria, $concurso, $results) {
        $key = "loterias_{$loteria}_{$concurso}";
        set_transient($key, $results, 12 * HOUR_IN_SECONDS);
    }
}
