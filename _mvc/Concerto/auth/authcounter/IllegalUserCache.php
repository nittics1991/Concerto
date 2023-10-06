<?php

/**
*   IllegalUserCache
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\auth\authcounter;

use RuntimeException;
use Psr\SimpleCache\CacheInterface;
use Concerto\standard\Server;

class IllegalUserCache
{
    /**
    *   @var CacheInterface
    */
    protected CacheInterface $cache;

    /**
    *   @var int
    */
    protected int $ttl;

    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    *   @param int $ttl
    */
    public function __construct(
        CacheInterface $cache,
        int $ttl = 60 * 15
    ) {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
    *   has
    *
    *   @return bool
    */
    public function has(): bool
    {
        $id = $this->getId();

        return $this->cache->has($id);
    }

    /**
    *   set
    *
    *   @param mixed $val
    *   @return static
    */
    public function set(
        mixed $val = null
    ): static {
        $id = $this->getId();

        if (!$this->cache->has($id)) {
            $val = strval($val ?? date(DATE_ATOM));

            $this->cache->set($id, $val, $this->ttl);
        }

        return $this;
    }

    /**
    *   ID
    *
    *   @return string
    */
    protected function getId(): string
    {
        $ip = Server::has('x-forwarded-for') ?
            Server::get('x-forwarded-for') :
            Server::get('remote_addr');

        $id = mb_ereg_replace('\.', '_', strval($ip));

        if (empty($id)) {
            throw new RuntimeException(
                "could not get IP address"
            );
        }

        return $id;
    }
}
