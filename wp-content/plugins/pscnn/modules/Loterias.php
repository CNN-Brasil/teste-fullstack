<?php

namespace PSCNN\Modules;
use PSCNN\Modules\Thirdy\Redis;

class Loterias {
    /**
     *
     * Method Loterias::search considers the parameters and searches for the requested loteria's data.
     *
     * @since 0.0.1
     *
     * @param string $loteria - loteria's name
     * @param mixed $concurso - "ultimo" or concurso's number
     *
     */

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

    /**
     * Method Loterias::list responds directly to the API with the requested loteria's data.
     *
     * @since 0.0.1
     *
     * @param \WP_REST_Request $request - It is provided automatically.
     *
     */

    static public function list(\WP_REST_Request $request) {
        $loteria = $request->get_param('loteria');
        $concurso = $request->get_param('concurso');
        $concurso = ($concurso === 'ultimo') ? 'latest' : $concurso;

        wp_send_json(self::search($loteria, $concurso), 200);
    }

    /**
     * Method Loterias::store store loteria's data in WP database
     *
     * @since 0.0.1
     *
     * @param array $data - loteria's data for store
     *
     * @return bool
     */

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

        return !is_wp_error($done);
    }

    /**
     * Method Loterias::get_from_cache get loteria's data from cache
     *
     * @since 0.0.1
     *
     * @param string $loteria - loteria's name
     * @param mixed $concurso - "ultimo" or concurso's number
     *
     */

    static protected function get_from_cache($loteria, $concurso) {
        $from_cache = Redis::get("{$loteria}:{$concurso}");

        if ($from_cache === null) {
            return [];
        }

        return json_decode($from_cache, true);
    }

    /**
     * Method Loterias::get_data_for_cache get prepared data to caching
     *
     * @since 0.0.1
     *
     * @param array $data - loteria's data for prepare to caching
     *
     * @return string
     */

    static protected function get_data_for_cache($data) {
        // phpcs:ignore
        return json_encode($data);
    }

    /**
     * Method Loterias::get_from_db get loteria's data from WP database or cache
     *
     * @since 0.0.1
     *
     * @param string $loteria - loteria's name
     * @param mixed $concurso - "ultimo" or concurso's number
     *
     */

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

    /**
     * Method Loterias::get_from_api get loteria's data from external API or cache
     *
     * @since 0.0.1
     *
     * @param string $loteria - loteria's name
     * @param mixed $concurso - "ultimo" or concurso's number
     *
     */

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
