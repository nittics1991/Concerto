<?php

/**
*   RememberMe
*
*   @version 220202
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
    *   keyName
    *
    *   @var string
    */
    protected $keyName = 'randomno';

    /**
    *   cache
    *
    *   @var CacheInterface
    */
    protected $cache;

    /**
    *   randomNoGenerator
    *
    *   @var RandomNumberGenaratorInterface
    */
    protected $randomNoGenerator;

    /**
    *   ttl
    *
    *   @var int
    */
    protected $ttl;

    /**
    *   cookie
    *
    *   @var CacheInterface
    */
    protected $cookie;

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

        if (!$randomNo) {
            return false;
        }
        return $this->cache->has($randomNo);
    }

    /**
    *   登録
    *
    *   @param string $userId
    *   @return $this
    */
    public function register(string $userId)
    {
        $randomNo = $this->randomNoGenerator->generate();
        if ($this->cache->has($randomNo)) {
            $this->register($userId);
            return $this;
        }

        $this->cache->set($randomNo, $userId, $this->ttl);
        $this->cookie->set($this->keyName, $randomNo, $this->ttl);
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
        $userId = $this->cache->get($randomNo ?? '', '');

        if (empty($userId)) {
            throw new RuntimeException(
                "user ID not found"
            );
        }
        return $userId;
    }

    /**
    *   Cookieへの登録名設定
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
