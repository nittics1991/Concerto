<?php

/**
*   キャッシュプール(Memcache)
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\cache;

use Exception;
use Memcache;
use RuntimeException;
use Concerto\cache\{
    CacheException,
    CacheItemPool,
    InvalidArgumentException
};

class MemcachePool extends CacheItemPool
{
    /**
    *   @var Memcache
    */
    protected Memcache $adapter;

    /**
    *   圧縮指定
    *
    *   @var int
    */
    protected int $compressed;

    /**
    *   __construct
    *
    *   @param string $namespace
    *   @param Memcache $memcache
    *   @param bool $compressed 圧縮指定
    */
    public function __construct(
        string $namespace,
        Memcache $memcache,
        bool $compressed = false
    ) {
        parent::__construct($namespace);

        $this->adapter = $memcache;

        $this->compressed = $compressed ?
            MEMCACHE_COMPRESSED :
            0;
    }

    /**
    *   キーマップ取得
    *
    *   @return string[]
    *   @throw CacheException
    */
    public function getKeys(): array
    {
        $result = [];

        try {
            $items = $this->adapter->getStats('items');
            if ($items === false) {
                throw new RuntimeException(
                    "failure Memcache status:items"
                );
            }

            foreach ((array)$items['items'] as $slabid => $item) {
                $cachedump = $this->adapter->getStats(
                    'cachedump',
                    $slabid,
                    (int)$item['number']
                );

                if ($cachedump === false) {
                    throw new RuntimeException(
                        "failure Memcache status:items.{$slabid}"
                    );
                }

                foreach (array_keys((array)$cachedump) as $key) {
                    if (mb_strpos($key, $this->namespace) === 0) {
                        $result[] = $key;
                    }
                }
            }
        } catch (Exception $e) {
            throw new CacheException(
                "failed to get status",
                0,
                $e
            );
        }

        return $result;
    }

    /**
    *   キャッシュ取得
    *
    *   @param string[] $ids
    *   @return mixed[] [[key => val], ...]
    *   @throws InvalidArgumentException
    */
    protected function fetch(
        array $ids
    ): array {
        $result = [];

        foreach ($ids as $id) {
            if (($gets = $this->adapter->get($id)) === false) {
                throw new InvalidArgumentException(
                    "not have:{$id}"
                );
            }
            $result[$id] = $gets;
        }

        return $result;
    }

    /**
    *   全キャッシュ削除
    *
    *   @return bool
    */
    protected function doClear(): bool
    {
        try {
            $ids = $this->getKeys();
        } catch (Exception $e) {
            return false;
        }

        return $this->doDelete($ids);
    }

    /**
    *   キャッシュ削除
    *
    *   @param string[] $ids
    *   @return bool
    */
    protected function doDelete(
        array $ids
    ): bool {
        $result = true;

        try {
            foreach ($ids as $id) {
                if ($this->adapter->delete($id) === false) {
                    $result = false;
                }
            }
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
    *   キャッシュ保存
    *
    *   @return string[] 保存したキー
    */
    protected function doSave(): array
    {
        $saved = [];

        try {
            foreach ($this->deferred as $key => $item) {
                $id = $this->makeId($key);

                $expiry = method_exists($item, 'getExpiry') ?
                    $item->getExpiry() : 0;

                if (
                    $this->adapter->set(
                        $id,
                        $item->get(),
                        $this->compressed,
                        $expiry
                    )
                ) {
                    $saved[] = $key;
                }
            }
        } catch (Exception $e) {
            throw new CacheException(
                "failed to save",
                0,
                $e
            );
        }

        return $saved;
    }

    /**
    *   アイテム情報取得
    *
    *   @param string $id
    *   @return mixed ['size' => size, 'expiry' => unix time]
    *   @throw CacheException
    */
    public function getItemInfo(
        string $id
    ): mixed {
        try {
            $items = $this->adapter->getStats('items');

            if ($items === false) {
                throw new RuntimeException(
                    "failure Memcache status:items"
                );
            }

            foreach ((array)$items['items'] as $slabid => $item) {
                $cachedump = $this->adapter->getStats(
                    'cachedump',
                    $slabid,
                    (int)$item['number']
                );

                if ($cachedump === false) {
                    throw new RuntimeException(
                        "failure Memcache status:items.{$slabid}"
                    );
                }

                foreach ((array)$cachedump as $key => $val) {
                    if ("{$this->namespace}.{$id}" === $key) {
                        return [
                            'size' => $val[0],
                            'expiry' => $val[1]
                        ];
                    }
                }
            }

            return null;
        } catch (Exception $e) {
            throw new CacheException(
                "failed to get status",
                0,
                $e
            );
        }
    }
}
