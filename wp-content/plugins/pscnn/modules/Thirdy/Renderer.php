<?php

namespace PSCNN\Modules\Thirdy;
use \Phug\Renderer as Pug;

require_once __DIR__ . '/vendor/autoload.php';

class Renderer {
    protected static $renderer = null;

    /**
     * Method Renderer::get_renderer is a singleton pattern method
     *  which returns the \Phug\Renderer instance
     *
     *  @since 0.0.1
     *
     * @return \Phug\Renderer
     */

    static protected function get_renderer() {
        if (self::$renderer !== null) {
            return self::$renderer;
        }

        self::$renderer = new Pug();

        return self::$renderer;
    }

    /**
     * Method Renderer::render convert's Pug string to HTML.
     *
     * @since 0.0.1
     *
     * @param string $pug_strings - The Pug template strings
     * @param array $data - The data to the template
     *
     * @return string
     */

    static public function render($pug_strings, $data = []): string {
        return self::get_renderer()->render($pug_strings, $data);
    }

    /**
     *
     * Method Renderer::renderFile convert's a Pug file to HTML strings.
     *
     * @since 0.0.1
     *
     * @param string $path - The path to Pug template file
     * @param array $data - The data to the template
     *
     * @return string
     */

    static public function renderFile($path, $data = []): string {
        return self::get_renderer()->renderFile($path, $data);
    }
}
