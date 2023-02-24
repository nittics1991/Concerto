<?php

/**
*   SimpleCacheTrait
*
*   @version 220118
*/

declare(strict_types=1);

namespace Concerto\cache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\InvalidArgumentException;

trait SimpleCacheTrait
{
    /**
    *   初期有効期間(sec)
    *
    *   @var int
    */
    protected int $defaultLifeTime = 0;

    /**
    *   {inherit}
    */
    public function getMultiple(
        iterable $keys,
        mixed $default = null
    ): iterable {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    /**
    *   {inherit}
    */
    public function setMultiple(
        iterable $values,
        null|int|\DateInterval $ttl = null
    ): bool {
        foreach ($values as $key => $value) {
            $result = $this->set($key, $value, $ttl);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }

    /**
    *   {inherit}
    */
    public function deleteMultiple(
        iterable $keys
    ): bool {
        $result = [];
        foreach ($keys as $key) {
            $result = $this->delete($key);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }

    /**
    *   {inherit}
    */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
    *   キー確認
    *
    *   @param string $key
    *   @return void
    *   @thows InvalidArgumentException
    */
    protected function validateKey(string $key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "key must be type string:{$key}"
            );
        }
        return;
    }

    /**
    *   保存期限の変換
    *
    *   @param null|int|\DateInterval $ttl
    *   @return int
    */
    protected function parseExpire(
        null|int|\DateInterval $ttl
    ) {
        if (is_int($ttl)) {
            return $ttl;
        }

        if ($ttl === null) {
            return $this->defaultLifeTime;
        }

        if ($ttl instanceof DateInterval) {
            $now = new DateTimeImmutable();
            $expire = $now->add($ttl);
            return (int)$expire->format('U') -
                (int)$now->format('U');
        }
    }

    /**
    *   setDefaultLifeTime
    *
    *   @param int $ttl
    *   @return $this
    */
    public function setDefaultLifeTime(
        int $ttl
    ) {
        $this->defaultLifeTime = $ttl;
        return $this;
    }
}
