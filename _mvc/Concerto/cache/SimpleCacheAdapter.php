<?php

/**
*   SimpleCacheAdapter
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\cache;

use DateInterval;
use DateTimeImmutable;
use Concerto\cache\{
    CacheItem,
    InvalidArgumentException
};
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

class SimpleCacheAdapter implements CacheInterface
{
    /**
    *   @var CacheItemPoolInterface
    */
    protected CacheItemPoolInterface $cache;

    /**
    *   @var int
    */
    protected int $ttl;

    /**
    *   __construct
    *
    *   @param CacheItemPoolInterface $cache
    *   @param int $ttl
    */
    public function __construct(
        CacheItemPoolInterface $cache,
        int $ttl = null
    ) {
        $this->cache = $cache;

        $this->ttl = is_int($ttl) ? $ttl : 86400;
    }

    /**
    *   @inheritDoc
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "key must be type string"
            );
        }

        if (!$this->cache->hasItem($key)) {
            return $default;
        }

        $packed = $this->cache->getItem($key)->get();

        //unserialize失敗と同じなので
        if ($packed === 'b:0;') {
            return false;
        }

        if (!is_string($packed)) {
            return false;
        }

        if (($value = @unserialize($packed)) === false) {
            return $default;
        }

        return $value;
    }

    /**
    *   @inheritDoc
    */
    public function set(
        string $key,
        mixed $value,
        null|int|\DateInterval $ttl = null
    ): bool {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "key must be type string"
            );
        }

        if (is_null($ttl)) {
            $ttl = $this->ttl;
        } elseif (is_int($ttl)) {
            //nop
        } elseif ($ttl instanceof DateInterval) {
            $ttl = (int)(new DateTimeImmutable())
                ->add($ttl)
                ->format('U') - time();
        } else {
            throw new InvalidArgumentException(
                "ttl must be type int | DateInterval"
            );
        }

        $value = serialize($value);

        $item = new CacheItem($key, $value, $ttl);

        return $this->cache->save($item);
    }

    /**
    *   @inheritDoc
    */
    public function delete(
        string $key
    ): bool {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "key must be type string"
            );
        }

        return $this->cache->deleteItem($key);
    }

    /**
    *   @inheritDoc
    */
    public function clear(): bool
    {
        return $this->cache->clear();
    }

    /**
    *   @inheritDoc
    */
    public function getMultiple(
        iterable $keys,
        mixed $default = null
    ): iterable {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException(
                "keys must be type itelable"
            );
        }

        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
    *   @inheritDoc
    */
    public function setMultiple(
        iterable $values,
        null|int|\DateInterval $ttl = null
    ): bool {
        if (!is_iterable($values)) {
            throw new InvalidArgumentException(
                "keys must be type itelable"
            );
        }

        $result = true;

        foreach ($values as $key => $val) {
            $result = $this->set($key, $val, $ttl) &&
                $result;
        }

        return $result;
    }

    /**
    *   @inheritDoc
    */
    public function deleteMultiple(
        iterable $keys
    ): bool {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException(
                "keys must be type itelable"
            );
        }

        $result = true;

        foreach ($keys as $key) {
            $result = $this->delete($key) &&
                $result;
        }

        return $result;
    }

    /**
    *   @inheritDoc
    */
    public function has(
        string $key
    ): bool {
        return $this->cache->hasItem($key);
    }
}
