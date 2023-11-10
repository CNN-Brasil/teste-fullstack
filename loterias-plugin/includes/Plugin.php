<?php
/*
Plugin Name: Loterias Plugin
Description: Um plugin para exibir resultados de loterias da caixa, utilizando a API do Guto Alves. (https://github.com/guto-alves/loterias-api)
Version: 1.0
Author: Gregory Lima
*/


defined( constant_name: 'ABSPATH' ) || exit ;

final class Plugin
{
    private $version = "0.0.1";

    private static $_instance = null;

    //add shortcode
    public function __construct() {
        add_shortcode('loterias', array($this, 'loterias_shortcode'));
    }

    public static function getInstance(): ?Plugin
    {
        if ( is_null( self::$_instance) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function checkInstance()
    {
        //
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    // ao ativar o plugin
    public function activate()
    {
        Activate::activate();
    }

    // ao desativar
    public function deactivate()
    {
        Deactivate::deactivate();
    }

    public function loterias_shortcode($atts) {
        $atts = shortcode_atts(
            array(
                'loteria' => 'megasena',
                'concurso' => 'ultimo',
            ),
            $atts,
            'loterias'
        );

        $loteria = $atts['loteria'];
        $concurso = $atts['concurso'];

        if ($concurso === 'ultimo') {
            $url = "https://loteriascaixa-api.herokuapp.com/api/$loteria/latest";
            $cache_key = 'loterias_' . $loteria . '_' . $concurso;
            $resultados = $this->consultar_api_e_armazenar($loteria, $url, $concurso, $cache_key);
        } else {
            $post_id = $this->verificar_se_concurso_existe($loteria, $concurso);
            if ($post_id) {
                $resultados = $this->obter_resultados_do_post($post_id);
            } else {
                $url = "https://loteriascaixa-api.herokuapp.com/api/$loteria/$concurso";
                $resultados = $this->consultar_api_e_armazenar($loteria, $url, $concurso);
            }
        }

        $output = $this->gerar_saida_formatada($resultados);

        return $output;
    }

    private function consultar_api_e_armazenar($loteria, $url, $concurso = 'ultimo', $cache_key = null) {
        $cached_data = false;
        $cache_expiration = 1800; //por 1/2 hora
        //verifica se é ultimo
        if ($concurso === 'ultimo'){
            $cached_data = get_transient($cache_key);
        }

        if (false === $cached_data) {
            // Se não houver cache válido, faça a chamada à API
            $response = wp_remote_get($url);

            if (is_array($response) && !is_wp_error($response)) {
                $data = wp_remote_retrieve_body($response);
                if ($cache_key){
                    set_transient($cache_key, $data, $cache_expiration);
                }
            } else {
                // Lidere com erros na chamada da API, se necessário
                $data = false;
            }
        } else {
            // Use os dados em cache
            $data = $cached_data;
        }

        $data_array = array();

        if ($data){
            $data_array = json_decode($data, true);
            $n_concurso = $data_array['concurso'];
            $conc_exist = $this->verificar_se_concurso_existe($loteria, $n_concurso);
        }

        if (empty($conc_exist) and $data){
            // Salvar os resultados no Custom Post Type
            $post_id = wp_insert_post(array(
                'post_title' => "$loteria-$n_concurso",
                'post_type' => 'loterias',
                'post_status' => 'publish',
                'post_content' => json_encode($data_array),
            ));
        }

        return $data_array;
    }

    private function verificar_se_concurso_existe($loteria, $concurso) {
        // Verificar se o concurso está no Custom Post Type
        $titulo_post = $loteria . '-' . $concurso;

        $args = array(
            'post_type' => 'loterias',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'title' => $titulo_post,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $loteria_post = $query->posts[0]; // Obter o primeiro post do tipo loterias encontrado
            // Agora você pode usar as propriedades do post, por exemplo:
            $loteria_id = $loteria_post->ID;
            $loteria_title = $loteria_post->post_title;
            $loteria_content = $loteria_post->post_content;
            // Faça o que precisar com os dados do post
        } else {
            // Post do tipo loterias não encontrado
        }

        // Restaurar o post global
        wp_reset_postdata();

        if ($loteria_id) {
            // O post com o título desejado foi encontrado
            return $loteria_id;
        } else {
            // O post não foi encontrado
            return false;
        }
    }

    private function obter_resultados_do_post($post_id) {
        // get_post_field para obter o campo post_content do post
        $content = get_post_field('post_content', $post_id);

        // Verifique se há conteúdo no post
        if (!empty($content)) {
            // Decodifique o JSON em um array
            $resultados = json_decode($content, true);

            // Verifique se a decodificação foi bem-sucedida
            if ($resultados !== null) {
                return $resultados;
            }
        }

        // Se não houver conteúdo ou se a decodificação falhar.
        return false;
    }

    private function gerar_saida_formatada($resultados) {
        // Lógica para gerar a saída formatada dos resultados de acordo com o layout do Figma
        $CH = new CreateHtml();
        return $CH->gerar_html_loteria($resultados);
    }

}
