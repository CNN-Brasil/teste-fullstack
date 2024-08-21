<?php

namespace LotteryChallenge;

/**
 * Class CacheManager
 * @package LotteryChallenge
 * 
 * Gerenciador de cache para armazenamento temporário de dados
 */

class CacheManager
{
    /**
     * @var int $cache_duration Duração do cache em segundos (padrão: 1 hora)
     */
    private $cache_duration;

    /**
     * Constructor.
     * 
     * @param int $cache_duration Duração do cache em segundos
     */
    public function __construct($cache_duration = 3600)
    {
        $this->cache_duration = $cache_duration;
    }

    /**
     * Obtém os dados armazenados em cache.
     * 
     * @param string $key chave do cache
     * @return mixed Dados armazenados em cache mistos ou falso se não houver dados
     */
    public function get_cached_data($key)
    {
        return get_transient($key);
    }

    /**
     * Armazena os dados em cache.
     * 
     * @param string $key chave do cache
     * @param mixed $data Dados a serem armazenados em cache
     * @return bool Verdadeiro se os dados foram armazenados com sucesso, falso caso contrário
     */
    public function set_cache($key, $data)
    {
        return set_transient($key, $data, $this->cache_duration);
    }

    /**
     * Limpa os dados armazenados em cache.
     * 
     * @param string $key chave do cache
     * @return bool Verdadeiro se os dados foram limpos com sucesso, falso caso contrário
     */
    public function clear_cache($key)
    {
        return delete_transient($key);
    }
}