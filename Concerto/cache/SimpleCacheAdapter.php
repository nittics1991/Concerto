<?php

/**
 *   SimpleCacheAdapter
 *
 * @version 181029
 */

declare(strict_types=1);

namespace Concerto\cache;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;
use Psr\Cache\CacheItemPoolInterface;
use Concerto\cache\CacheItem;
use Concerto\cache\InvalidArgumentException;

class SimpleCacheAdapter implements CacheInterface
{
    /**
     *   cache
     *
     * @var CacheItemPoolInterface
     **/
    protected $cache;
    
    /**
     *   cache
     *
     * @var int
     **/
    protected $ttl;
    
    /**
     *   コンストラクタ
     *
     * @param CacheItemPoolInterface $cache
     * @param int                    $ttl
     **/
    public function __construct(CacheItemPoolInterface $cache, $ttl = null)
    {
        $this->cache = $cache;
        $this->ttl = (is_int($ttl)) ? $ttl : 86400;
    }
    
    /**
     *   {inherit}
     **/
    public function get($key, $default = null)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("key must be type string");
        }
        
        if (!$this->cache->hasItem($key)) {
            return $default;
        }
        $packed = $this->cache->getItem($key)->get();
        
        //unserialize失敗と同じなので
        if ($packed === 'b:0;') {
            return false;
        }
        
        if (($value = @unserialize($packed)) === false) {
            return $default;
        }
        return $value;
    }
    
    /**
     *   {inherit}
     **/
    public function set($key, $value, $ttl = null)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("key must be type string");
        }
        
        if (is_null($ttl)) {
            $ttl = $this->ttl;
        } elseif (is_int($ttl)) {
            //nop
        } elseif ($ttl instanceof DateInterval) {
            $ttl = (int)(new DateTime())->add($ttl)->format('U') - time();
        } else {
            throw new InvalidArgumentException(
                "ttl must be type int | DateInterval"
            );
        }
        $value = serialize($value);
        $item = new CacheItem($key, $value, $ttl);
        return $this->cache->save($item);
    }
    
    /**
     *   {inherit}
     **/
    public function delete($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("key must be type string");
        }
        return $this->cache->deleteItem($key);
    }
    
    /**
     *   {inherit}
     **/
    public function clear()
    {
        return $this->cache->clear();
    }
    
    /**
     *   {inherit}
     **/
    public function getMultiple($keys, $default = null)
    {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException("keys must be type itelable");
        }
        
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }
    
    /**
     *   {inherit}
     **/
    public function setMultiple($values, $ttl = null)
    {
        if (!is_iterable($values)) {
            throw new InvalidArgumentException("keys must be type itelable");
        }
        
        $result = true;
        foreach ($values as $key => $val) {
            $result = $this->set($key, $val, $ttl) & $result;
        }
        return $result;
    }
    
    /**
     *   {inherit}
     **/
    public function deleteMultiple($keys)
    {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException("keys must be type itelable");
        }
        
        $result = true;
        foreach ($keys as $key) {
            $result = $this->delete($key) & $result;
        }
        return $result;
    }
    
    /**
     *   {inherit}
     **/
    public function has($key)
    {
        return $this->cache->hasItem($key);
    }
}
