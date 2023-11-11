<?php
namespace LoteriasPlugin;

class LoteriasTinyMCE {
    
    public function __construct() {
        add_action('admin_init', array($this, 'addLoteriaButton'));
    }

    public function addLoteriaButton() {
        if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
            add_filter('mce_buttons', [$this, 'registerLoteriaButton']);
            add_filter('mce_external_plugins', [$this, 'addLoteriaButtonScript']);
            wp_enqueue_style( 'admin-loteria-plugin',  plugin_dir_url(__DIR__) . 'assets/admin/css/loteria-button.css', array(), "1.0", 'all' );
        }
    }

    public function registerLoteriaButton($buttons) {
        array_push($buttons, 'loteria_button');
        return $buttons;
    }

    public function addLoteriaButtonScript($plugin_array) {

        $plugin_array['loteria_button'] = plugin_dir_url(__DIR__) . 'assets/admin/js/loteria-button.js';
        wp_register_script( 'loterias-select-options', '', [], '', true );
        wp_enqueue_script( 'loterias-select-options'  );

        $tipo_concurso = get_terms( array(
            'taxonomy'   => 'tipo_concurso',
            'hide_empty' => false,
        ) );

        foreach ($tipo_concurso as $index => $term) {
            $selectLoterias[] = '"'.$term->name.'"';
        }
        wp_add_inline_script( 'loterias-select-options', 'let selectLoterias = ['.implode(",", $selectLoterias).'];');

        return $plugin_array;
    }

}