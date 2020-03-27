<?php

/**
 *   キャッシュプール(Memcache)
 *
 * @version 191216
 */

declare(strict_types=1);

namespace Concerto\cache;

use Exception;
use Memcache;
use Concerto\cache\CacheException;
use Concerto\cache\CacheItemPool;
use Concerto\cache\InvalidArgumentException;

class MemcachePool extends CacheItemPool
{
    /**
     *   キャッシュアダプタ
     *
     * @var Memcache
     **/
    protected $adapter;
    
    /**
     *   圧縮指定
     *
     * @var int
     **/
    protected $compressed;
    
    /**
     *   コンストラクタ
     *
     * @param string   $namespace
     * @param Memcache $memcache
     * @param bool     $compressed 圧縮指定
     **/
    public function __construct(
        $namespace,
        Memcache $memcache,
        $compressed = false
    ) {
        parent::__construct($namespace);
        $this->adapter = $memcache;
        $this->compressed = $compressed ? MEMCACHE_COMPRESSED : 0;
    }
    
    /**
     *   キーマップ取得
     *
     * @return array
     * @throw  CacheException
     */
    public function getKeys()
    {
        $result = [];
        
        try {
            $items = $this->adapter->getStats('items');
            foreach ((array)$items['items'] as $slabid => $item) {
                $cachedump = $this->adapter->getStats(
                    'cachedump',
                    $slabid,
                    (int)$item['number']
                );
                
                foreach (array_keys((array)$cachedump) as $key) {
                    if (mb_strpos($key, $this->namespace) === 0) {
                        $result[] = $key;
                    }
                }
            }
        } catch (Exception $e) {
            throw new CacheException("failed to get status", 0, $e);
        }
        return $result;
    }
    
    /**
     *   キャッシュ取得
     *
     * @param  array $ids
     * @return array [[key => val], ...]
     * @throws InvalidArgumentException
     */
    protected function fetch(array $ids)
    {
        $result = [];
        
        foreach ($ids as $id) {
            if (($gets = $this->adapter->get($id)) === false) {
                throw new InvalidArgumentException("not have:{$id}");
            }
            $result[$id] = $gets;
        }
        return $result;
    }
    
    /**
     *   全キャッシュ削除
     *
     * @return bool
     */
    protected function doClear()
    {
        $ids = $this->getKeys();
        return $this->doDelete($ids);
    }
    
    /**
     *   キャッシュ削除
     *
     * @param  array $ids
     * @return bool
     */
    protected function doDelete(array $ids)
    {
        $result = true;
        
        try {
            foreach ($ids as $id) {
                if ($this->adapter->delete($id) == false) {
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
     * @return array 保存したキー
     */
    protected function doSave()
    {
        $saved = [];
        
        try {
            foreach ($this->deferred as $key => $item) {
                $id = $this->makeId($key);
                
                if (
                    $this->adapter->set(
                        $id,
                        $item->get(),
                        $this->compressed,
                        $item->getExpiry()
                    )
                ) {
                    $saved[] = $key;
                }
            }
        } catch (Exception $e) {
            throw new CacheException("failed to save", 0, $e);
        }
        return $saved;
    }
    
    /**
     *   アイテム情報取得
     *
     * @param  string $id
     * @return array|null ['size' => size, 'expiry' => unix time]
     * @throw  CacheException
     **/
    public function getItemInfo($id)
    {
        try {
            $items = $this->adapter->getStats('items');
            foreach ((array)$items['items'] as $slabid => $item) {
                $cachedump = $this->adapter->getStats(
                    'cachedump',
                    $slabid,
                    (int)$item['number']
                );
                
                foreach ((array)$cachedump as $key => $val) {
                    if ("{$this->namespace}.{$id}" == $key) {
                        return ['size' => $val[0], 'expiry' => $val[1]];
                    }
                }
            }
            return null;
        } catch (Exception $e) {
            throw new CacheException("failed to get status", 0, $e);
        }
    }
}
