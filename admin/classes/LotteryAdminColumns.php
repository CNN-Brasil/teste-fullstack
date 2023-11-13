<?php

namespace CnnPluginBr\Admin;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

class LotteryAdminColumns {
    /**
     * Get class instance
     *
     * @var object|null
     * @since 1.0.0
     */
    protected static ?object $instance = null;
    
    public function __construct() {
        add_action( 'manage_cnn-lottery_posts_columns', [ $this, 'setLotteryColumnsLabel' ] );
        add_action( 'manage_cnn-lottery_posts_custom_column', [ $this, 'renderLotteryColumnShortcode' ], 20, 2 );
    }
    
    /**
     * Return an instance of this class.
     *
     * @return LotteryAdminColumns A single instance of this class.
     * @since 1.0.0
     */
    public static function getInstance(): LotteryAdminColumns {
        /** If the single instance hasn't been set, set it now. */
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    /**
     * Define new custom columns
     *
     * @param array $columns
     *
     * @return array
     * @since 1.0.0
     */
    public function setLotteryColumnsLabel( array $columns ): array {
        $lottery_date = $columns['date'];
        unset( $columns['date'] );
        $columns['cnn-lottery_shortcode'] = __( 'Shortcode', 'cnn-lottery' );
        $columns['date']                  = $lottery_date;
        
        return $columns;
    }
    
    /**
     * Define new column content
     *
     * @param string $column
     * @param int $post_id
     *
     * @return void
     * @since 1.0.0
     */
    public function renderLotteryColumnShortcode( string $column, int $post_id ): void {
        if ( $column === 'cnn-lottery_shortcode' ):
            $lottery  = get_post_meta( $post_id, 'loteria', true );
            $concurso = get_post_meta( $post_id, 'concurso', true );
            echo wp_kses_post( "[sorteio loteria=\"$lottery\" concurso=\"$concurso\"]" );
        endif;
    }
}
