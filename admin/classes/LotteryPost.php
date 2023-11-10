<?php

namespace CnnPluginBr\Admin;

use CnnPluginBr\Utils\LotteryUtils;
use wpdb;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

class LotteryPost {
    
    /**
     * Get class instance
     *
     * @var object|null
     * @since 1.0.0
     */
    protected static ?object $instance = null;
    
    /**
     * Return an instance of this class.
     *
     * @return LotteryPost A single instance of this class.
     * @since 1.0.0
     */
    public static function getInstance(): LotteryPost {
        /** If the single instance hasn't been set, set it now. */
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    /**
     * @param string $lottery
     * @param string $contest
     *
     * @return string
     * @since 1.0.0
     */
    public static function getLottery( string $lottery, string $contest ): string {
        $instance     = self::getInstance();
        $transient    = "{$lottery}_$contest";
        $lottery_data = get_transient( $transient );
        $lottery_id   = 0;
        if ( ! $lottery_data ) {
            if ( $instance->checkPostExists( $lottery, $contest ) ) {
                $lottery_id = $instance->checkPostExists( $lottery, $contest );
            } else {
                $get_lottery = $instance->getLotteryApiData( $lottery, $contest );
                if ( ! empty( $get_lottery ) ) {
                    $lottery_id = $instance->insertLotteryPost( $get_lottery );
                }
            }
            set_transient( $transient, get_post_meta( $lottery_id, 'lottery_data', true ), 60 );
            $lottery_data = get_transient( $transient );
            
            if ( $lottery_id === 0 ) {
                $lottery_data = '';
                delete_transient( $transient );
            }
        }
        
        return $lottery_data;
    }
    
    /**
     * Insert lottery post
     *
     * @param string $lottery_data
     *
     * @return int
     * @since 1.0.0
     */
    private function insertLotteryPost( string $lottery_data ): int {
        $lottery = json_decode( $lottery_data, true );
        extract( $lottery );
        $args = [
            'post_title'  => "$loteria concurso $concurso de $data",
            'post_type'   => 'cnn-lottery',
            'post_status' => 'publish',
            'post_date'   => LotteryUtils::normalize_date( $data ) . ' ' . current_time( 'H:i:s' ),
            'meta_input'  => [
                'loteria'      => $loteria,
                'concurso'     => $concurso,
                'lottery_data' => $lottery_data,
            ]
        ];
        
        return wp_insert_post( $args );
    }
    
    /**
     * Check if lottery/contest exists
     *
     * @param string $lottery
     * @param string $contest
     *
     * @return int
     * @since 1.0.0
     */
    private function checkPostExists( string $lottery, string $contest ): int {
        $db = self::wpdb();
        if ( $contest === 'latest' ) {
            $contest = json_decode( self::getLotteryApiData( $lottery, $contest ), true )['concurso'];
        }
        $query         = "
            SELECT
                {$db->prefix}posts.ID AS lottery_id
            FROM {$db->prefix}posts
            INNER JOIN {$db->prefix}postmeta AS loteria ON loteria.post_id = wp_posts.ID
            INNER JOIN {$db->prefix}postmeta AS concurso ON concurso.post_id = wp_posts.ID
            WHERE {$db->prefix}posts.post_type = 'cnn-lottery'
            AND concurso.meta_key = 'concurso'
            AND concurso.meta_value = '$contest'
            AND loteria.meta_key = 'loteria'
            AND loteria.meta_value = '$lottery' LIMIT 1;";
        $get_lotteries = $db->get_var( $query );
        if ( ! is_null( $get_lotteries ) ) {
            return (int) $get_lotteries;
        }
        
        return 0;
    }
    
    /**
     * Get lottery data from api
     *
     * @param string $lottery
     * @param string $contest
     *
     * @return string
     * @since 1.0.0
     */
    private function getLotteryApiData( string $lottery, string $contest ): string {
        $get_lottery = new LotteryAPI();
        $transient   = "request_api_{$lottery}_$contest";
        $request     = get_transient( $transient );
        if ( ! $request ) {
            $request = $get_lottery->requestLotteryContest( $lottery, $contest );
            set_transient( $transient, $request, 60 );
        }
        
        return $request;
    }
    
    /**
     * WPDB Instance
     *
     * @return wpdb
     * @since 1.0.0
     */
    private function wpdb(): wpdb {
        global $wpdb;
        
        return $wpdb;
    }
}