<?php

namespace CnnPluginBr\Admin;

use CnnPluginBr\Front\LotteryView;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

class LotteryShortCode {
    
    /**
     * Get class instance
     *
     * @var object|null
     * @since 1.0.0
     */
    protected static ?object $instance = null;
    
    public function __construct() {
        /** Create a new shortcode */
        add_shortcode( 'sorteio', [ $this, 'setLotteryShortCode' ] );
    }
    
    /**
     * Return an instance of this class.
     *
     * @return LotteryShortCode A single instance of this class.
     * @since 1.0.0
     */
    public static function getInstance(): LotteryShortCode {
        /** If the single instance hasn't been set, set it now. */
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    /**
     * Set up a new lottery shortcode
     *
     * @param array $atts
     *
     * @return string
     * @since 1.0.0
     */
    public function setLotteryShortCode( array $atts ): string {
        $atts = shortcode_atts( [ 'loteria' => 'megasena', 'concurso' => 'latest' ], $atts, 'sorteio' );
        
        return LotteryView::render( [ 'template' => 'shortcode.php', 'args' => $atts ] );
    }
}
