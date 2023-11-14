<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasTinyMCE - Responsável por adicionar um botão personalizado ao editor TinyMCE.
 */
class LoteriasTinyMCE {
    
    /**
     * Construtor da classe. Adiciona a ação 'admin_init' para chamar o método 'addLoteriaButton'.
     */
    public function __construct() {
        add_action('admin_init', array($this, 'addLoteriaButton'));
    }

    /**
     * Adiciona o botão personalizado ao editor TinyMCE.
     */
    public function addLoteriaButton() {
        // Verifica as permissões do usuário para editar posts e páginas
        if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
            // Adiciona filtros para os botões e scripts necessários
            add_filter('mce_buttons_2', [$this, 'registerLoteriaButton']);
            add_filter('mce_external_plugins', [$this, 'addLoteriaButtonScript']);
            // Enfila o estilo CSS do botão no painel de administração
            wp_enqueue_style( 'admin-loteria-plugin',  plugin_dir_url(__DIR__) . 'assets/admin/css/loteria-button.css', array(), "1.0", 'all' );
        }
    }

    /**
     * Registra o botão personalizado no TinyMCE.
     *
     * @param array $buttons Array atual de botões do TinyMCE.
     * @return array Array modificado com o novo botão.
     */
    public function registerLoteriaButton($buttons) {
        // Adiciona o identificador do botão personalizado ao array de botões
        array_push($buttons, 'loteria_button');
        return $buttons;
    }

    /**
     * Adiciona o script do botão personalizado ao TinyMCE.
     *
     * @param array $plugin_array Array atual de scripts do TinyMCE.
     * @return array Array modificado com o novo script.
     */
    public function addLoteriaButtonScript($plugin_array) {
        // Adiciona o script do botão personalizado
        $plugin_array['loteria_button'] = plugin_dir_url(__DIR__) . 'assets/admin/js/loteria-button.js';
        // Registra um script vazio para permitir a inclusão de variáveis personalizadas
        wp_register_script( 'loterias-select-options', '', [], '', true );
        wp_enqueue_script( 'loterias-select-options'  );

        // Obtém os termos da taxonomia 'tipo_concurso'
        $tipo_concurso = get_terms( array(
            'taxonomy'   => 'tipo_concurso',
            'hide_empty' => false,
        ) );

        // Cria um array com os nomes dos termos
        foreach ($tipo_concurso as $index => $term) {
            $selectLoterias[] = '"'.$term->name.'"';
        }

        // Adiciona uma variável JavaScript com os nomes dos termos
        wp_add_inline_script( 'loterias-select-options', 'let selectLoterias = ['.implode(",", $selectLoterias).'];');

        return $plugin_array;
    }

}