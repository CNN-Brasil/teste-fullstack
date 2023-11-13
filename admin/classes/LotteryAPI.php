<?php

namespace CnnPluginBr\Admin;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

class LotteryAPI {
    
    /**
     * Define API url
     *
     * @var string
     * @since 1.0.0
     */
    protected string $api_url = "https://loteriascaixa-api.herokuapp.com/api/";
    
    /**
     * Define allowed lotteries
     *
     * @var array
     * @since 1.0.0
     */
    protected static array $allowed_lotteries = [
        "maismilionaria",
        "megasena",
        "lotofacil",
        "quina",
        "lotomania",
        "timemania",
        "duplasena",
        "federal",
        "diadesorte",
        "supersete"
    ];
    
    /**
     * Retrieve lottery contest
     *
     * @param string $lottery
     * @param string $contest
     *
     * @return string
     * @since 1.0.0
     */
    public function requestLotteryContest( string $lottery, string $contest = 'latest' ): string {
        $output = '';
        if ( in_array( $lottery, self::$allowed_lotteries ) ) {
            $url = $this->api_url . "$lottery/$contest";
            // @TODO implementar o vip_safe_wp_remote_get() ao enviar para o ambiente da VIP, caso use um host próprio, ignore.
            $request = wp_remote_get( $url );//phpcs:ignore
            if ( ! is_wp_error( $request ) ) {
                $response = wp_remote_retrieve_body( $request );
                if ( ! empty( $response ) ) {
                    $output = $response;
                }
            }
        }
        
        return $output;
    }
}
