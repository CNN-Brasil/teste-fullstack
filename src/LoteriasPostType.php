<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasPostType - Responsável por registrar o tipo de postagem 'loterias'.
 */
class LoteriasPostType {

    /**
     * Registra o tipo de postagem 'loterias'.
     */
    public function register() {
        // Rótulos para o tipo de postagem
        $labels = [
            'name' => 'Loterias',
            'singular_name' => 'Loteria',
            'menu_name' => 'Loterias',
        ];

        // Argumentos para o registro do tipo de postagem
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'rewrite' => ['slug' => 'loterias'],
        ];

        // Registra o tipo de postagem
        register_post_type('loterias', $args);
    }
}