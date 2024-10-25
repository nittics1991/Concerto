<?php

/**
*   RememberMe
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\rememberme;

use RuntimeException;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\CookieCache;
use Concerto\hashing\RandomNumberGenaratorInterface;

class RememberMe
{
    /**
    *   @var string
    */
    protected string $keyName = 'randomno';

    /**
    *   @var CacheInterface
    */
    protected CacheInterface $cache;

    /**
    *   @var RandomNumberGenaratorInterface
    */
    protected RandomNumberGenaratorInterface $randomNoGenerator;

    /**
    *   @var int
    */
    protected int $ttl;

    /**
    *   @var CacheInterface
    */
    protected CacheInterface $cookie;

    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    *   @param RandomNumberGenaratorInterface $randomNoGenerator
    *   @param int $ttl
    *   @param string $namespace
    */
    public function __construct(
        CacheInterface $cache,
        RandomNumberGenaratorInterface $randomNoGenerator,
        int $ttl = 60 * 60 * 8,
        string $namespace = 'rememberme'
    ) {
        $this->cache = $cache;

        $this->randomNoGenerator = $randomNoGenerator;

        $this->ttl = $ttl;

        $this->cookie = new CookieCache($namespace);
    }

    /**
    *   登録済み判定
    *
    *   @return bool
    */
    public function isRegistered(): bool
    {
        $randomNo = $this->cookie->get($this->keyName, null);

        if (!$randomNo || !is_string($randomNo)) {
            return false;
        }

        return $this->cache->has($randomNo);
    }

    /**
    *   登録
    *
    *   @param string $userId
    *   @return static
    */
    public function register(
        string $userId
    ): static {
        $randomNo = $this->randomNoGenerator->generate();
        if ($this->cache->has($randomNo)) {
            $this->register($userId);
            return $this;
        }

        $this->cache->set(
            $randomNo,
            $userId,
            $this->ttl
        );

        $this->cookie->set(
            $this->keyName,
            $randomNo,
            $this->ttl
        );

        return $this;
    }

    /**
    *   ユーザID取得
    *
    *   @return string
    */
    public function getId(): string
    {
        $randomNo = $this->cookie->get($this->keyName);

        $userId = $this->cache->get(strval($randomNo), '');

        if (empty($userId)) {
            throw new RuntimeException(
                "user ID not found"
            );
        }

        if (!is_string($userId)) {
            throw new RuntimeException(
                "invalid type"
            );
        }

        return $userId;
    }

    /**
    *   Cookieへの登録名設定
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
