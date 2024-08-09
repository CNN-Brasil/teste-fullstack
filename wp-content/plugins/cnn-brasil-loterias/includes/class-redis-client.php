<?php
/**
 * Redis Client Class
 *
 * This class provides a simple Redis client implementation.
 *
 * @package CNN_Brasil_Loterias
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Redis_Client {
    /**
     * @var \Redis|null
     */
    private $redis;

    public function __construct() {
        if (class_exists('\Redis')) {
            $this->redis = new \Redis();
            try {
                $this->redis->connect('redis', 6379);
            } catch (\Exception $e) {
                error_log('Redis connection failed: ' . $e->getMessage());
                $this->redis = null;
            }
        } else {
            error_log('Redis extension is not installed.');
            $this->redis = null;
        }
    }

    /**
     * Get a value from Redis.
     *
     * @param string $key
     * @return mixed|false
     */
    public function get($key) {
        if ($this->redis) {
            return $this->redis->get($key);
        }
        return false;
    }

    /**
     * Set a value in Redis.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $expiry
     * @return bool
     */
    public function set($key, $value, $expiry = null) {
        if ($this->redis) {
            if ($expiry) {
                return $this->redis->setex($key, $expiry, $value);
            }
            return $this->redis->set($key, $value);
        }
        return false;
    }

    /**
     * Delete a key from Redis.
     *
     * @param string $key
     * @return bool
     */
    public function delete($key) {
        if ($this->redis) {
            return $this->redis->del($key) > 0;
        }
        return false;
    }
}