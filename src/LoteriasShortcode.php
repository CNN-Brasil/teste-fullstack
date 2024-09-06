<?php

namespace LoteriasPlugin;

class LoteriasShortcode
{
    private $api;

    /**
     * Constructor that registers the shortcode and initializes the API class.
     *
     * This method registers the shortcode [loterias] and initializes the API handler
     * to interact with the external lottery API.
     */
    public function __construct()
    {
        add_shortcode('loterias', [$this, 'render_shortcode']);
        $this->api = new LoteriasApi();
    }

    /**
     * Renders the shortcode for displaying lottery results.
     *
     * This method handles the shortcode [loterias] with attributes for 'loteria' (lottery type)
     * and 'concurso' (contest number or 'ultimo' for the latest contest). It fetches the result
     * either from the database or the API and returns a formatted template.
     *
     * @param array $atts Shortcode attributes ('loteria' and 'concurso').
     * @return string Rendered HTML template for the lottery result.
     */
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

    /**
     * Saves the lottery result in a custom post type.
     *
     * This method inserts a new 'loterias' post with the contest information and updates
     * the metadata for the lottery results such as numbers, date, and prize details.
     *
     * @param string $lottery The lottery name.
     * @param object $result The lottery result object from the API.
     * @return int The ID of the saved post.
     */
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

    /**
     * Retrieves an existing contest from the database.
     *
     * This method checks if a contest result already exists in the database for a given lottery
     * and contest number, using a cached result if available.
     *
     * @param string $lottery The lottery name.
     * @param string|int $contest The contest number.
     * @return object|false The contest post object if found, otherwise false.
     */
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

    /**
     * Renders the lottery result template for a contest.
     *
     * This method gathers the metadata for the lottery contest and includes a PHP template
     * to render the results for display on the front-end.
     *
     * @param object $post The post object representing the contest.
     * @return string The rendered HTML template for the contest.
     */
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

    /**
     * Converts a date into the weekday in Portuguese.
     *
     * This method converts a given date string (in 'd/m/Y' format) into the day of the week
     *
     * @param string $date The date string in 'd/m/Y' format.
     * @return string The day of the week in Portuguese.
     */
    private function get_weekday($date)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $date);
        if ($date) {
            $formatter = new \IntlDateFormatter('pt_BR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, 'EEEE');
            return $formatter->format($date);
        }
    }
}
