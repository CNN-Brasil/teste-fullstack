<?php
// wp-content/plugins/loterias-plugin/src/LoteriasPluginInit.php

namespace LoteriasPlugin;

class LoteriasPluginInit {
    public function __construct() {
        require_once plugin_dir_path(__FILE__) . 'LoteriasPlugin.php';
        $plugin = new LoteriasPlugin();
    }
}
