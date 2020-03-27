<?php

/**
 *   キャッシュプール
 *
 * @version 180119
 */

declare(strict_types=1);

namespace Concerto\cache;

use Exception;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Concerto\cache\InvalidArgumentException;
use Concerto\cache\CacheItem;

abstract class CacheItemPool implements CacheItemPoolInterface
{
    /**
     *   名前空間
     *
     * @var string
     **/
    protected $namespace;
    
    /**
     *   遅延保存用キャッシュ
     *
     * @var array
     **/
    protected $deferred = [];
    
    /**
     *   キャッシュ取得
     *
     * @param   array $ids
     * @return  array [[key => val], ...]
     * @throws  InvalidArgumentException
     * @example key not found to be throw InvalidArgumentException
     */
    abstract protected function fetch(array $ids);
    
    /**
     *   全キャッシュ削除
     *
     * @return bool
     */
    abstract protected function doClear();
    
    /**
     *   キャッシュ削除
     *
     * @param  array $ids
     * @return bool
     */
    abstract protected function doDelete(array $ids);
    
    /**
     *   キャッシュ保存
     *
     * @return array 保存したキー
     */
    abstract protected function doSave();
    
    /**
     *   コンストラクタ
     *
     * @param string $namespace
     **/
    public function __construct($namespace)
    {
        if (!is_string($namespace) || (strlen($namespace) > 20)) {
            throw new InvalidArgumentException(
                "max length is char(20):{$namespace}"
            );
        }
        $this->namespace = $namespace;
    }
    
    /**
     *   デストラクタ
     **/
    public function __destruct()
    {
        $this->commit();
    }
    
    /**
     *   アイテム取得
     *
     * @param  string $key
     * @return CacheItemInterface or null
     **/
    public function getItem($key)
    {
        $vals = $this->getItems([$key]);
        return array_shift($vals);
    }
    
    /**
     *   アイテム取得(一括)
     *
     * @param  array $keys
     * @return array CacheItemInterface or null
     */
    public function getItems(array $keys = [])
    {
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
     * @param  string $key
     * @return bool
     */
    public function hasItem($key)
    {
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
     * @return bool
     */
    public function clear()
    {
        $this->deferred = [];
        return $this->doClear();
    }
    
    /**
     *   アイテム削除
     *
     * @param  string $key
     * @return bool
     * @throws InvalidArgumentException
     */
    public function deleteItem($key)
    {
        return $this->deleteItems([$key]);
    }
    
    /**
     *   複数アイテム削除
     *
     * @param  array $keys
     * @return bool
     * @throws InvalidArgumentException
     */
    public function deleteItems(array $keys)
    {
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
     * @param  CacheItemInterface $item
     * @return bool
     */
    public function save(CacheItemInterface $item)
    {
        $this->deferred[$item->getKey()] = $item;
        return $this->commit();
    }
    
    /**
     *   遅延保存
     *
     * @param  CacheItemInterface $item
     * @return bool
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    /**
     *   アイテム保存
     *
     * @return bool
     */
    public function commit()
    {
        if (empty($this->deferred)) {
            return true;
        }
        
        $saved = $this->doSave();
        
        if (count($this->deferred) == count((array)$saved)) {
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
     * @param  string $key
     * @return string
     **/
    protected function makeId($key)
    {
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
     * @param  array $vals keys [key=>val, ...]
     * @return array CacheItemInterface or null
     */
    protected function createItems(array $vals)
    {
        $result = [];
        
        foreach ($vals as $id => $val) {
            $split = explode('.', $id);
            array_shift($split);
            $key = implode('.', $split);
            
            $result[$key] = new CacheItem($key, $val, 0, true);
        }
        return $result;
    }
}
