<?php

/**
*   AuthCounter
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authcounter;

use Psr\SimpleCache\CacheInterface;

class AuthCounter
{
    /**
    *   @var string
    */
    protected string $keyName = 'failureCount';

    /**
    *   @var int
    */
    protected int $ttl = 60 * 60 * 24;

    /**
    *   @var CacheInterface
    */
    protected CacheInterface $cache;

    /**
    *   @var int
    */
    protected int $limit;

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
    *   @return static
    */
    public function increment(): static
    {
        $count = $this->cache->get($this->keyName) ?? 0;

        $this->cache->set(
            $this->keyName,
            ++$count,
            $this->ttl
        );

        return $this;
    }

    /**
    *   到達
    *
    *   @return bool
    */
    public function reached(): bool
    {
        $count = $this->cache->get($this->keyName) ?? 0;

        return $count >= $this->limit;
    }

    /**
    *   クリア
    *
    *   @return static
    */
    public function clear(): static
    {
        $this->cache->set($this->keyName, 0, -1);

        return $this;
    }

    /**
    *   Cacheへの登録名設定
    *
    *   @param string $keyName
    *   @return static
    */
    public function setKeyName(
        string $keyName
    ): static {
        $this->keyName = $keyName;

        return $this;
    }
}
