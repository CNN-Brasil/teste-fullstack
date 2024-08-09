<?php

namespace PSCNN\Modules\Thirdy;

use Predis\Client;

require_once __DIR__ . '/vendor/autoload.php';

class Redis {
    static protected $redis = null;

    /**
     * Method Redis::get_instance is a singleton pattern method
     *  which returns the \Predis\Client instance
     *
     *  @since 0.0.1
     *
     * @return \Predis\Client
     */

    static protected function get_instance(): Client {
        if (self::$redis !== null) {
            return self::$redis;
        }

        self::$redis = new Client('tcp://' . REDIS_ADDRESS, [
            'parameters' => [
                'password' => REDIS_PASSWORD,
            ],
        ]);

        return self::$redis;
    }

    /**
     * Method Redis::get searches a key in cache database
     *
     *  @since 0.0.1
     *
     * @param string $key - The key for search
     *
     * @return string | null
     */

    static public function get($key): string | null {
        return self::get_instance()->get($key);
    }

    /**
     * Method Redis::set records data list in cache database
     *
     *  @since 0.0.1
     *
     * @param array $list - The data
     * @param integer $ttl - Timelife in seconds
     *
     * @return bool
     */

    static public function set($list, $ttl = DAY_IN_SECONDS): bool {
        $done = true;

        foreach ($list as $key => $value) {
            $status = self::get_instance()->setex($key, $ttl, $value);

            if ($status->getPayload() !== "OK") {
                $done = false;

                break;
            };
        }

        return $done;
    }
}
