<?php

namespace LoteriasPlugin;

class LoteriasShortcode
{
    private $api;

    public function __construct()
    {
        add_shortcode('loterias', [$this, 'render_shortcode']);
        $this->api = new LoteriasApi();
    }

    public function render_shortcode($atts)
    {
        $atts = shortcode_atts([
            'loteria' => 'megasena',
            'concurso' => 'ultimo',
        ], $atts, 'loterias');

        $lottery  = sanitize_text_field($atts['loteria']);
        $contest = sanitize_text_field($atts['concurso']);

        if ($contest === 'ultimo') {
            $api_result = $this->api->fetch_lottery_api_result($lottery, $contest);
            if (isset($api_result->concurso)) {
                $contest = $api_result->concurso;
            } else {
                return '<p>Erro ao buscar resultados da loteria.</p>';
            }
        }

        $existing_contest = $this->get_existing_contest($lottery, $contest);
        if ($existing_contest) {
            return $this->get_template($existing_contest);
        }

        if (!isset($api_result)) {
            $api_result = $this->api->fetch_lottery_api_result($lottery, $contest);
        }

        if (!$api_result) {
            return '<p>Erro ao buscar resultados da loteria.</p>';
        }

        $post_id = $this->save_lottery_result($lottery, $api_result);
        return $this->get_template(get_post($post_id));
    }

    private function save_lottery_result($lottery, $result)
    {
        $post_id = wp_insert_post([
            'post_title'  => 'Concurso ' . $result->concurso . ' - ' . ucfirst($lottery),
            'post_type'   => 'loterias',
            'post_status' => 'publish',
        ]);

        update_post_meta($post_id, 'lottery_name', $lottery);
        update_post_meta($post_id, 'lottery_contest', $result->concurso);
        update_post_meta($post_id, 'lottery_date', $result->data ?? '');
        update_post_meta($post_id, 'lottery_location', $result->local ?? '');
        update_post_meta($post_id, 'lottery_numbers', implode(',', (array) $result->dezenas));
        update_post_meta($post_id, 'lottery_collected_amount', $result->valorArrecadado ?? '');
        update_post_meta($post_id, 'lottery_accumulated', $result->acumulou ?? false);

        if (isset($result->premiacoes) && is_array($result->premiacoes)) {
            foreach ($result->premiacoes as $index => $prize) {
                update_post_meta($post_id, 'lottery_prize_' . $index . '_description', $prize->descricao ?? '');
                update_post_meta($post_id, 'lottery_prize_' . $index . '_winners', $prize->ganhadores ?? 0);
                update_post_meta($post_id, 'lottery_prize_' . $index . '_value', $prize->valorPremio ?? 0);
            }
        }

        return $post_id;
    }

    private function get_existing_contest($lottery, $contest)
    {
        $cache_key = 'existing_contest_' . $lottery . '_' . $contest;
        $cached_result = get_transient($cache_key);

        if ($cached_result) {
            return $cached_result;
        }

        $query = new \WP_Query([
            'post_type' => 'loterias',
            'meta_query' => [
                [
                    'key'     => 'lottery_contest',
                    'value'   => $contest,
                    'compare' => '='
                ],
                [
                    'key'     => 'lottery_name',
                    'value'   => $lottery,
                    'compare' => '='
                ]
            ]
        ]);

        $result = $query->have_posts() ? $query->posts[0] : false;
        set_transient($cache_key, $result, HOUR_IN_SECONDS);
        return $result;
    }

    private function get_template($post)
    {
        $lottery_name = get_post_meta($post->ID, 'lottery_name', true);
        $contest = get_post_meta($post->ID, 'lottery_contest', true);
        $date = get_post_meta($post->ID, 'lottery_date', true);
        $weekday = $this->get_weekday($date);
        $location = get_post_meta($post->ID, 'lottery_location', true);
        $numbers = explode(',', get_post_meta($post->ID, 'lottery_numbers', true));
        $collected_amount = get_post_meta($post->ID, 'lottery_collected_amount', true);
        $accumulated = get_post_meta($post->ID, 'lottery_accumulated', true);

        $prizes = [];
        $index = 0;
        while ($description = get_post_meta($post->ID, 'lottery_prize_' . $index . '_description', true)) {
            $prizes[] = [
                'description' => $description,
                'winners' => get_post_meta($post->ID, 'lottery_prize_' . $index . '_winners', true),
                'value' => get_post_meta($post->ID, 'lottery_prize_' . $index . '_value', true),
            ];
            $index++;
        }

        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/loteria-result.php';
        return ob_get_clean();
    }

    private function get_weekday($date)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $date);
        if ($date) {
            $formatter = new \IntlDateFormatter('pt_BR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, 'EEEE');
            return $formatter->format($date);
        }
    }
}
