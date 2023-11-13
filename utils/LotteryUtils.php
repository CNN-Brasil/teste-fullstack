<?php

namespace CnnPluginBr\Utils;
/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

class LotteryUtils {
    
    /**
     * @param float $val
     *
     * @return string
     * @since 1.0.0
     */
    public static function forma_money( float $val ): string {
        return number_format( round( $val, 2 ), 2, ',', '.' );
    }
    
    /**
     * @param string $date
     *
     * @return string
     * @since 1.0.0
     */
    public static function normalize_date( string $date ): string {
        $new_date = explode( '/', $date );
        $new_date = array_reverse( $new_date );
        
        return implode( '-', $new_date );
    }
}
