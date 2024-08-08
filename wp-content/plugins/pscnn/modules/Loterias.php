<?php

namespace PSCNN\Modules;
use PSCNN\Modules\Thirdy\Redis;

class Loterias {
    static public function search($loteria, $concurso) {
        $data = null;

        if ($concurso === 'latest') {
            $data = self::get_from_api($loteria, $concurso);
            $concurso = $data['concurso'];

            if ( empty(self::get_from_db($loteria, $concurso)) ) {
                self::store($data);
            };

            return $data;
        }

        $data = self::get_from_db($loteria, $concurso);

        if (empty($data)) {
            $data = self::get_from_api($loteria, $concurso);
            self::store($data);

            return $data;
        }

        return $data;
    }

    static public function list(\WP_REST_Request $request) {
        $loteria = $request->get_param('loteria');
        $concurso = $request->get_param('concurso');
        $concurso = ($concurso === 'ultimo') ? 'latest' : $concurso;

        wp_send_json(self::search($loteria, $concurso), 200);
    }

    static protected function store($data) {
        $loteria = $data['loteria'];
        $concurso = $data['concurso'];

        $done = wp_insert_post([
            'post_type' => Post_types::LOTERIAS,
            'post_title' => "{$loteria} {$concurso}",
            'post_status' => 'publish',
            'meta_input' => [
                'loteria' => $loteria,
                'concurso' => $data['concurso'],
                'dados_concurso' => $data,
            ],

        ]);

        return $done;
    }

    static protected function get_from_cache($loteria, $concurso) {
        return json_decode(Redis::get("{$loteria}:{$concurso}"), true);
    }

    static protected function get_data_for_cache($data) {
        // phpcs:ignore
        return json_encode($data);
    }

    static protected function get_from_db($loteria, $concurso) {
        $from_cache = self::get_from_cache($loteria, $concurso);

        if (!empty($from_cache)) {
            return $from_cache;
        }

        // phpcs:disable
        $posts = get_posts([
            'post_type' => Post_Types::LOTERIAS,
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => [
                [
                    'key' => 'loteria',
                    'value' => $loteria,
                    'compare' => '=',
                ],
                [
                    'key' => 'concurso',
                    'value' => $concurso,
                    'compare' => '=',
                ],
            ],

        ]);
        // phpcs:enable

        $data = get_post_meta($posts[0]->ID, 'dados_concurso', true);
        Redis::set(["{$loteria}:{$concurso}" => self::get_data_for_cache($data)]);

        return $data;
    }

    static protected function get_from_api($loteria, $concurso) {
        $from_cache = self::get_from_cache($loteria, $concurso);

        if (!empty($from_cache)) {
            return $from_cache;
        }

        $url = "https://loteriascaixa-api.herokuapp.com/api/{$loteria}/{$concurso}";
        // phpcs:ignore
        $data = wp_remote_get($url);

        $data = json_decode($data['body'], true);
        Redis::set(["{$loteria}:{$concurso}" => self::get_data_for_cache($data)]);

        return $data;
    }
}
