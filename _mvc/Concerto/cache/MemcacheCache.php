<?php

/**
*   MemcacheCache
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\cache;

use Memcache;
use Concerto\cache\{
    CacheException,
    SimpleCacheTrait
};
use Psr\SimpleCache\CacheInterface;

class MemcacheCache implements CacheInterface
{
    use SimpleCacheTrait;

    /**
    *   @var string
    */
    protected string $namespace;

    /**
    *   @var Memcache
    */
    protected Memcache $cache;

    /**
    *   @var mixed[]
    */
    protected array $options = [
        'persistent' => null,
        'weight' => null,
        'timeout' => null,
        'retry_interval' => null,
        'status' => null,
        'failure_callback' => null,
        'timeoutms' => null,
    ];

    /**
    *   @var bool
    */
    protected bool $compressed = false;

    /**
    *   __construct
    *
    *   @param string $namespace
    *   @param string $host
    *   @param int $port
    *   @param mixed[] $options
    */
    public function __construct(
        string $namespace = 'memcache',
        string $host = '127.0.0.1',
        int $port = 11211,
        array $options = null
    ) {
        $this->cache = $this->createConnection(
            $host,
            $port,
            $options
        );

        $this->namespace = $namespace;
    }

    /**
    *   __destruct
    */
    public function __destruct()
    {
        if (isset($this->cache)) {
            $this->cache->close();
        }
    }

    /**
    *   コネクション確立
    *
    *   @param string $host
    *   @param int  $port
    *   @param mixed[] $options
    *   @return Memcache
    */
    protected function createConnection(
        string $host,
        int $port,
        array $options = null
    ): Memcache {
        $cache = new Memcache();

        $options = array_replace(
            $this->options,
            (array)$options
        );

        $options = array_intersect_key(
            $options,
            $this->options
        );

        $options = array_filter(
            $options,
            function ($option) {
                return isset($option);
            }
        );

        $arguments = [$host, $port] + $options;

        $result = call_user_func_array(
            [$cache, 'addServer'],
            $arguments
        );

        if ($result === false) {
            throw new CacheException(
                "add server error:{$host}:{$port}"
            );
        }

        if ($cache->connect($host, $port) === false) {
            throw new CacheException(
                "connect error:{$host}:{$port}"
            );
        }

        return clone $cache;
    }

    /**
    *   圧縮を使用
    *
    *   @return static
    */
    public function compress(): static
    {
        $this->compressed = true;

        return $this;
    }

    /**
    *   @inheritDoc
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        $this->validateKey($key);

        $result = $this->cache->get("{$this->namespace}_{$key}");

        return $result === false ? $default : $result;
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

        $ttl = $this->parseExpire($ttl);

        //注意:expireのunix time設定は出来ない
        return $this->cache->set(
            "{$this->namespace}_{$key}",
            $value,
            $this->compressed ? MEMCACHE_COMPRESSED : 0,
            $ttl
        );
    }

    /**
    *   @inheritDoc
    */
    public function delete(
        string $key
    ): bool {
        $this->validateKey($key);

        return $this->cache->delete("{$this->namespace}_{$key}");
    }

    /**
    *   @inheritDoc
    */
    public function clear(): bool
    {
        $keys = $this->getKeys();

        foreach ($keys as $key) {
            $result = $this->delete($key);

            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
    *   キーマップ取得
    *
    *   @return string[]
    */
    public function getKeys(): array
    {
        $result = [];

        $items = $this->cache->getStats('items');

        foreach ((array)$items['items'] as $slabid => $item) {
            $cachedump = $this->cache->getStats(
                'cachedump',
                $slabid,
                $item['number']
            );

            foreach (array_keys((array)$cachedump) as $key) {
                if (mb_strpos($key, "{$this->namespace}_") === 0) {
                    $splited = mb_split('_', $key);

                    if (
                        $splited === false ||
                        !isset($splited[1])
                    ) {
                        return $result;
                    }

                    $result[] = $splited[1];
                }
            }
        }

        return $result;
    }
}
