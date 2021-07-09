<?php

/**
*   AuthCounter
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\auth\authcounter;

use Psr\SimpleCache\CacheInterface;

class AuthCounter
{
    /**
    *   keyName
    *
    *   @var string
    */
    protected $keyName = 'failureCount';

    /**
    *   ttl
    *
    *   @var int
    */
    protected $ttl = 60 * 60 * 24;

    /**
    *   cache
    *
    *   @var CacheInterface
    */
    protected $cache;

    /**
    *   limit
    *
    *   @var int
    */
    protected $limit;

    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    *   @param int $limit リトライ回数
    */
    public function __construct(
        CacheInterface $cache,
        int $limit = 5
    ) {
        $this->cache = $cache;
        $this->limit = $limit;
    }

    /**
    *   増加
    *
    *   @return $this
    */
    public function increment()
    {
        $count = $this->cache->get($this->keyName) ?? 0;
        $this->cache->set($this->keyName, ++$count, $this->ttl);
        return $this;
    }

    /**
    *   到達
    *
    *   @return bool
    */
    public function reached()
    {
        $count = $this->cache->get($this->keyName) ?? 0;
        return $count >= $this->limit;
    }

    /**
    *   クリア
    *
    *   @return $this
    */
    public function clear()
    {
        $this->cache->set($this->keyName, 0, -1);
        return $this;
    }

    /**
    *   Cacheへの登録名設定
    *
    *   @param string $keyName
    *   @return $this
    */
    public function setKeyName(string $keyName)
    {
        $this->keyName = $keyName;
        return $this;
    }
}
