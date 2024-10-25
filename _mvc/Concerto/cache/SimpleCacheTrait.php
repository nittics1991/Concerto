<?php

/**
*   SimpleCacheTrait
*
*   @version 221201
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
    *   @var int
    */
    protected int $defaultLifeTime = 0;

    /**
    *   @inheritDoc
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
    *   @inheritDoc
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
    *   @inheritDoc
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
    *   @inheritDoc
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
    protected function validateKey(
        string $key
    ): void {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "key must be type string:{$key}"
            );
        }
    }

    /**
    *   保存期限の変換
    *
    *   @param null|int|\DateInterval $ttl
    *   @return int
    */
    protected function parseExpire(
        null|int|\DateInterval $ttl
    ): int {
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
    *   @return static
    */
    public function setDefaultLifeTime(
        int $ttl
    ): static {
        $this->defaultLifeTime = $ttl;

        return $this;
    }
}
