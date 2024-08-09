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

    /**
     * Method API::add_routes adds the API routes.
     *
     * @since 0.0.1
     *
     * @return void
     */

    static protected function add_routes($namespace, $routes) {
        foreach ($routes as $route) {
            $name = $route['name'];
            unset($route['name']);

            register_rest_route($namespace, $name, $route);
        }
    }

    /**
     * Method API::init performs the initial actions of the class.
     * and is called by global initialization in the rest_api_init action
     *
     * @since 0.0.1
     *
     * @return void
     */

    static public function init() {
        self::add_routes('hlsmelo/pscnn/v1', self::ROUTES);
    }
}
