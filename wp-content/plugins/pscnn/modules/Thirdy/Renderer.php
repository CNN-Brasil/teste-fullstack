<?php

namespace PSCNN\Modules\Thirdy;

require_once __DIR__ . '/vendor/autoload.php';

class Renderer {
    protected static $renderer = null;

    static protected function get_renderer() {
        if (self::$renderer !== null) {
            return self::$renderer;
        }

        self::$renderer = new \Phug\Renderer();

        return self::$renderer;
    }

    static public function render($view, $data = array()) {
        return self::get_renderer()->render($view, $data);
    }

    static public function renderFile($view, $data = array()) {
        return self::get_renderer()->renderFile($view, $data);
    }
}
