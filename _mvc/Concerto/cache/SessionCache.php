<?php

/**
*   SessionCache
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\cache;

use Concerto\cache\{
    CacheException,
    SimpleCacheTrait
};
use Psr\SimpleCache\CacheInterface;

class SessionCache implements CacheInterface
{
    use SimpleCacheTrait;

    /**
    *   @var string
    */
    protected string $namespace;

    /**
    *   __construct
    *
    *   @param string $namespace
    */
    public function __construct(
        string $namespace = 'SessionCache'
    ) {
        $this->namespace = $namespace;
    }

    /**
    *   __destruct
    */
    public function __destruct()
    {
        $this->writeSession();
    }

    /**
    *   writeSession
    */
    protected function writeSession(): void
    {
        session_write_close();
    }

    /**
    *   startSession
    */
    protected function startSession(): void
    {
        if (
            session_status() !== PHP_SESSION_ACTIVE &&
            ! headers_sent()
        ) {
            session_start();
        }
    }

    /**
    *   @inheritDoc
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        $this->validateKey($key);

        $this->startSession();

        $result = $_SESSION[$this->namespace][$key] ?? $default;

        $this->writeSession();

        return $result;
    }

    /**
    *   @inheritDoc
    */
    public function set(
        string $key,
        mixed $value,
        null|int|\DateInterval $ttl = null
    ): bool {
        $this->validateKey($key);

        $this->startSession();

        $_SESSION[$this->namespace][$key] = $value;

        $this->writeSession();

        return true;
    }

    /**
    *   @inheritDoc
    */
    public function delete(
        string $key
    ): bool {
        $this->validateKey($key);

        $this->startSession();

        unset($_SESSION[$this->namespace][$key]);

        $this->writeSession();

        return true;
    }

    /**
    *   @inheritDoc
    */
    public function clear(): bool
    {
        $this->startSession();

        unset($_SESSION[$this->namespace]);

        $this->writeSession();

        return true;
    }

    /**
    *   セッションID再生成
    *
    *   @return bool
    */
    public function regenerateId(): bool
    {
        $result = session_regenerate_id(true);

        if ($result === false) {
            throw new CacheException(
                "session id regenerate error"
            );
        }

        return true;
    }
}
