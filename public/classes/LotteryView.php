<?php

namespace CnnPluginBr\Front;
/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

use CnnPluginBr\Admin\LotteryInit;

class LotteryView {
    
    /**
     * @param array $template template name and args, use keys 'template' to set
     *                        template name and ...args to pass another values
     * @param bool $is_admin
     *
     * @return string
     * @since 1.0.0
     */
    public static function render( array $template, bool $is_admin = false ): string {
        $path = 'public';
        if ( $is_admin ) {
            $path = 'admin';
        }
        $file_path = LotteryInit::getPluginDirPath() . "$path/templates/" . $template['template'];
        $output    = "Template não encontrado...";
        if ( file_exists( $file_path ) ) {
            ob_start();
            require $file_path;
            $output = ob_get_clean();
        }
        
        return $output;
    }
}
