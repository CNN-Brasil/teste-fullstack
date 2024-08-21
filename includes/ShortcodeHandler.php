<?php

namespace LotteryChallenge;

use LotteryChallenge\LotteryUtils;

/**
 * Class ShortcodeHandler
 * @package LotteryChallenge
 * 
 * Manipulador de shortcodes para renderização de resultados de loterias
 */
class ShortcodeHandler
{
    /**
     * @var LotteryAPI $lottery_api Instância da classe LotteryAPI
     * @var CacheManager $cache_manager Instância da classe CacheManager
     */
    private $lottery_api;
    private $cache_manager;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->lottery_api = new LotteryAPI();
        $this->cache_manager = new CacheManager();
    }

    /**
     * Registra o shortcode "loterias" para renderização de resultados de loterias
     */
    public function register_shortcode()
    {
        add_shortcode('loterias', [$this, 'render_shortcode']);
    }

    /**
     * Renderiza o shortcode "loterias" com os resultados da loteria
     * 
     * @param array $atts Atributos do shortcode
     * @return string HTML com os resultados da loteria
     */
    public function render_shortcode($atts)
    {
        $atts = shortcode_atts([
            'loteria' => 'megasena',
            'concurso' => 'latest'
        ], $atts, 'loterias');

        // Valida o parâmetro 'loteria'. Se não for uma loteria válida, retorna uma mensagem de erro
        if (!in_array($atts['loteria'], ['maismilionaria', 'megasena', 'lotofacil', 'quina', 'lotomania', 'timemania', 'duplasena', 'federal', 'diadesorte', 'supersete'])) {
            return '<div class="lottery-error">Loteria inválida ' . esc_html($atts['loteria']) . '. Escolha entre "maismilionaria", "megasena", "lotofacil", "quina", "lotomania", "timemania", "duplasena", "federal", "diadesorte" ou "supersete".</div>';
        }

        // Valida o parâmetro 'concurso'. Se não for um valor numérico ou "ultimo" ou "latest", retorna uma mensagem de erro
        if (!is_numeric($atts['concurso']) && $atts['concurso'] !== 'ultimo' && $atts['concurso'] !== 'latest') {
            return '<div class="lottery-error">Número do concurso ' . esc_html($atts['concurso']) . ' inválido. Informe um número válido ou "ultimo" para o último concurso.</div>';
        }

        $results = $this->get_lottery_results_data($atts['loteria'], $atts['concurso']);

        if (isset($results['error'])) {
            return '<div class="lottery-error">' . esc_html($results['message']) . '</div>';
        }

        return $this->render_template($results);
    }

    /**
     * Obtém os dados dos resultados da loteria
     * 
     * @param string $lottery Nome da loteria
     * @param string $contest Número do concurso
     * @return array Dados dos resultados da loteria
     */
    private function get_lottery_results_data($lottery, $contest)
    {
        // Verifica se o parâmetro "concurso" é "ultimo"
        if ($contest === 'ultimo') {
            $contest = 'latest';
        }

        $cache_key = $lottery . '_' . $contest;
        $results = $this->cache_manager->get_cached_data($cache_key);

        if (!$results) {
            $post_id = $this->check_existing_contest($lottery, $contest);
            if ($post_id) {
                $results = get_post_meta($post_id, '_lottery_result', true);
            } else {
                try {
                    $results = $this->lottery_api->get_lottery_results($lottery, $contest);
                } catch (\Exception $e) {
                    return ['error' => true, 'message' => 'Erro ao consultar a API: ' . $e->getMessage()];
                }

                if (isset($results['error'])) {
                    return $results;
                }

                if (!isset($results['concurso'])) {
                    return ['error' => true, 'message' => 'Nenhum resultado encontrado para o número do concurso ' . esc_html($contest) . ' da loteria ' . ucfirst(esc_html($lottery)) . '.'];
                }

                $this->save_lottery_result($lottery, $results);
            }

            $this->cache_manager->set_cache($cache_key, $results);
        }

        return $results;
    }

    /**
     * Verifica se o concurso da loteria já está salvo no CPT "Loterias"
     * 
     * @param string $lottery Nome da loteria
     * @param string $contest Número do concurso
     * @return int|bool ID do post existente se já estiver salvo, falso caso contrário
     */
    private function check_existing_contest($lottery, $contest)
    {
        $args = [
            'post_type' => 'loterias',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => '_lottery_name',
                    'value' => $lottery
                ],
                [
                    'key' => '_lottery_contest',
                    'value' => $contest
                ]
            ],
            'no_found_rows' => true
        ];

        $post_ids = get_posts($args);

        return !empty($post_ids) ? $post_ids[0] : false;
    }

    /**
     * Salva os resultados do concurso da loteria no CPT "Loterias"
     * 
     * @param string $lottery Nome da loteria
     * @param array $results Dados dos resultados do concurso
     * @return int|bool ID do post salvo se a operação for bem-sucedida, falso caso contrário
     */
    private function save_lottery_result($lottery, $results)
    {
        // Verifica se o concurso já está salvo no CPT "Loterias"
        $existing_post_id = $this->check_existing_contest($lottery, $results['concurso']);
        if ($existing_post_id) {
            return $existing_post_id; // Retorna o ID do post existente se já estiver salvo
        }

        $post_data = [
            'post_title' => sprintf('%s - Concurso %s', ucfirst($lottery), $results['concurso']),
            'post_type' => 'loterias',
            'post_status' => 'publish'
        ];

        $post_id = wp_insert_post($post_data);

        if ($post_id) {
            update_post_meta($post_id, '_lottery_name', $lottery);
            update_post_meta($post_id, '_lottery_contest', $results['concurso']);
            update_post_meta($post_id, '_lottery_result', $results);
        }

        return $post_id;
    }

    /**
     * Renderiza o template com os resultados da loteria
     * 
     * @param array $results Dados dos resultados da loteria
     * @return string HTML com os resultados da loteria
     */
    private function render_template($results)
    {
        $date = $results['data'];
        $formatted_date = LotteryUtils::format_date_with_day($date);

        if($results['loteria'] === 'megasena') {
            $mapped_awards = array_map(function ($award) {
                $award['descricao'] = LotteryUtils::map_award_descriptions($award['descricao']);
                return $award;
            }, $results['premiacoes']);
    
            $results['premiacoes'] = $mapped_awards;
        }

        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/lottery-template.php';
        return ob_get_clean();
    }
}