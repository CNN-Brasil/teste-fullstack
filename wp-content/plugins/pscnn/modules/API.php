<?php

namespace PSCNN\Modules;

class API {
    const ROUTES = [
        [
            'name' => 'loterias',
            'methods' => 'GET',
            'callback' => Loterias::class . '::list',
            'permission_callback' => '__return_true',
        ],
    ];

    static protected function add_routes($namespace, $routes) {
        foreach ($routes as $route) {
            $name = $route['name'];
            unset($route['name']);

            register_rest_route($namespace, $name, $route);
        }
    }

    static public function init() {
        self::add_routes('hlsmelo/pscnn/v1', self::ROUTES);
    }
}
