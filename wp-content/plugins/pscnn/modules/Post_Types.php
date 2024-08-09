<?php

namespace PSCNN\Modules;
use PSCNN\PSCNN;

class Post_Types {
    const LOTERIAS = 'loterias';

    /**
     * Method Post_Types::add registers a new post type.
     *
     * @since 0.0.1
     *
     * @return void
     */

    static protected function add(): void {
        register_post_type(self::LOTERIAS, [
            'label' => __('Loterias', PSCNN::TEXT_DOMAIN),
        ]);
    }

    /**
     * Method Post_Types::init performs the initial actions of the class.
     * and is called by global initialization
     *
     * @since 0.0.1
     *
     * @return void
     */

    static public function init(): void {
        self::add();
    }
}
