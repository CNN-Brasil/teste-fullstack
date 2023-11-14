<?php

namespace LoteriasPlugin;

/**
 * Classe LoteriasPluginInit - Responsável por inicializar o plugin Loterias.
 */
class LoteriasPluginInit {
    
    /**
     * Construtor da classe. Inclui o arquivo 'LoteriasPlugin.php' e instancia a classe LoteriasPlugin.
     */
    public function __construct() {
        // Inclui o arquivo 'LoteriasPlugin.php'
        require_once plugin_dir_path(__FILE__) . 'LoteriasPlugin.php';
        // Instancia a classe LoteriasPlugin
        $plugin = new LoteriasPlugin();
    }
}