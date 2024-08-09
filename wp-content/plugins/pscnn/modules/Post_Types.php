<?php

namespace PSCNN\Modules;
use PSCNN\PSCNN;

class Post_Types {
    const LOTERIAS = 'loterias';

    static protected function add() {
        register_post_type(self::LOTERIAS, [
            'label' => __('Loterias', PSCNN::TEXT_DOMAIN),
        ]);
    }

    static public function init() {
        self::add();
    }
}
