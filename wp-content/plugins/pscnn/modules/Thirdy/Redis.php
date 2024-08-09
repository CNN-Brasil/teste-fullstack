<?php

namespace PSCNN\Modules\Thirdy;

use Predis\Client;

require_once __DIR__ . '/vendor/autoload.php';

class Redis {
    static protected $redis = null;

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

    static public function get($key) {
        return self::get_instance()->get($key);
    }

    static public function set($list, $ttl = DAY_IN_SECONDS) {
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
