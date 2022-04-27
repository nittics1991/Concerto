<?php

/**
*   キャッシュプール(PdoCache)
*
*   @version 220127
*/

declare(strict_types=1);

namespace Concerto\cache;

use Exception;
use Memcache;
use RuntimeException;
use Concerto\cache\{
    CacheException,
    CacheItemPool,
    InvalidArgumentException,
    PdoCache,
};

class PdoCachePool extends CacheItemPool
{
    /**
    *   キャッシュアダプタ
    *
    *   @var PdoCache
    */
    protected PdoCache $adapter;

    /**
    *   __construct
    *
    *   @param string $namespace
    *   @param PdoCache $adapter
    */
    public function __construct(
        string $namespace,
        PdoCache $adapter,
    ) {
        parent::__construct($namespace);
        $this->adapter = $adapter;
    }

    /**
    *   {inherit}
    */
    protected function fetch(
        array $ids
    ) {
        $results = [];

        foreach ($ids as $id) {
            $results[$id] = $this->fetchCache($id);
        }
        return $results;
    }

    /**
    *   fetchCache
    *
    *   @param string $id
    *   @return mixed
    */
    protected function fetchCache(
        string $id
    ) {
        $random = bin2hex(random_bytes(16));
        $result = $this->adapter->get($id, $random);

        if ($result === $random) {
            throw new InvalidArgumentException(
                "not have:{$id}"
            );
        }
        return $result;
    }

    /**
    *   {inherit}
    */
    protected function doClear()
    {
        return $this->adapter->clear();
    }

    /**
    *   {inherit}
    */
    protected function doDelete(
        array $ids
    ) {
        $results = [];

        foreach ($ids as $id) {
            $results[] = $this->deleteCache($id);
        }
        return true;
    }

    /**
    *   deleteCache
    *
    *   @param string $id
    *   @return bool
    */
    protected function deleteCache(
        string $id
    ) {
        return $this->adapter->delete($id);
    }

    /**
    *   {inherit}
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
}
