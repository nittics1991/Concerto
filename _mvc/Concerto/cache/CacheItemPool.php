<?php

/**
*   キャッシュプール
*
*   @version 220117
*/

declare(strict_types=1);

namespace Concerto\cache;

use Exception;
use Concerto\cache\{
    CacheItem,
    InvalidArgumentException
};
use Psr\Cache\{
    CacheItemInterface,
    CacheItemPoolInterface
};

abstract class CacheItemPool implements CacheItemPoolInterface
{
    /**
    *   名前空間
    *
    *   @var string
    */
    protected string $namespace;

    /**
    *   遅延保存用キャッシュ
    *
    *   @var CacheItemInterface[]
    */
    protected array $deferred = [];

    /**
    *   キャッシュ取得
    *
    *   @param string[] $ids
    *   @return mixed[] [[key => val], ...]
    *   @throws InvalidArgumentException
    *   @example key not found to be throw InvalidArgumentException
    */
    abstract protected function fetch(
        array $ids
    );

    /**
    *   全キャッシュ削除
    *
    *   @return bool
    */
    abstract protected function doClear();

    /**
    *   キャッシュ削除
    *
    *   @param string[] $ids
    *   @return bool
    */
    abstract protected function doDelete(
        array $ids
    );

    /**
    *   キャッシュ保存
    *
    *   @return string[] 保存したキー
    */
    abstract protected function doSave();

    /**
    *   __construct
    *
    *   @param string $namespace
    */
    public function __construct(string $namespace)
    {
        if (
            !is_string($namespace) ||
            strlen($namespace) > 20
        ) {
            throw new InvalidArgumentException(
                "max length is char(20):{$namespace}"
            );
        }
        $this->namespace = $namespace;
    }

    /**
    *   デストラクタ
    */
    public function __destruct()
    {
        $this->commit();
    }

    /**
    *   アイテム取得
    *
    *   @param string $key
    *   @return CacheItemInterface
    *   @throws InvalidArgumentException
    */
    public function getItem(
        string $key
    ): CacheItemInterface {
        $vals = $this->getItems([$key]);

        if (!is_array($vals)) {
            $vals = iterator_to_array($vals);
        }

        $result = array_shift($vals);

        if ($result === null) {
            throw new InvalidArgumentException(
                "can not get item:{$key}"
            );
        }

        return $result;
    }

    /**
    *   アイテム取得(一括)
    *
    *   @param string[] $keys
    *   @return iterable
    */
    public function getItems(
        array $keys = []
    ): iterable {
        $this->commit();

        $ids = array_map(
            function ($val) {
                return $this->makeId($val);
            },
            $keys
        );
        $vals = $this->fetch($ids);
        return $this->createItems($vals);
    }

    /**
    *   アイテム有無
    *
    *   @param string $key
    *   @return bool
    */
    public function hasItem(
        string $key
    ): bool {
        $this->commit();
        $id = $this->makeId($key);

        try {
            $this->fetch([$id]);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
    *   アイテム削除(一括)
    *
    *   @return bool
    */
    public function clear(): bool
    {
        $this->deferred = [];
        return $this->doClear();
    }

    /**
    *   アイテム削除
    *
    *   @param string $key
    *   @return bool
    *   @throws InvalidArgumentException
    */
    public function deleteItem(
        string $key
    ): bool {
        return $this->deleteItems([$key]);
    }

    /**
    *   複数アイテム削除
    *
    *   @param string[] $keys
    *   @return bool
    *   @throws InvalidArgumentException
    */
    public function deleteItems(
        array $keys
    ): bool {
        $this->commit();

        $ids = array_map(
            function ($val) {
                return $this->makeId($val);
            },
            $keys
        );
        return $this->doDelete($ids);
    }

    /**
    *   保存
    *
    *   @param CacheItemInterface $item
    *   @return bool
    */
    public function save(
        CacheItemInterface $item
    ): bool {
        $this->deferred[$item->getKey()] = $item;
        return $this->commit();
    }

    /**
    *   遅延保存
    *
    *   @param CacheItemInterface $item
    *   @return bool
    */
    public function saveDeferred(
        CacheItemInterface $item
    ): bool {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    /**
    *   アイテム保存
    *
    *   @return bool
    */
    public function commit(): bool
    {
        if (empty($this->deferred)) {
            return true;
        }

        $saved = $this->doSave();

        if (
            count($this->deferred) ===
            count((array)$saved)
        ) {
            $this->deferred = [];
            return true;
        }

        foreach ((array)$saved as $key) {
            unset($this->deferred[$key]);
        }
        return false;
    }

    /**
    *   アイテムキー作成
    *
    *   @param string $key
    *   @return string
    */
    protected function makeId(
        string $key
    ): string {
        $result =  "{$this->namespace}.{$key}";

        if (strlen($result) > 64) {
            throw new InvalidArgumentException(
                "key max_length is char(44):{$key}"
            );
        }
        return $result;
    }

    /**
    *   データセットからCacheItem配列作成
    *
    *   @param mixed[] $vals keys [key=>val, ...]
    *   @return CacheItemInterface[] CacheItemInterface
    */
    protected function createItems(
        array $vals
    ): array {
        $result = [];

        foreach ($vals as $id => $val) {
            $split = explode('.', $id);
            array_shift($split);
            $key = implode('.', $split);

            $result[$key] =
                new CacheItem($key, $val, 0, true);
        }
        return $result;
    }
}
