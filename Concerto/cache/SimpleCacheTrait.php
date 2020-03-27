<?php

/**
 *   SimpleCacheTrait
 *
 * @version 190522
 **/

declare(strict_types=1);

namespace Concerto\cache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\InvalidArgumentException;
 
trait SimpleCacheTrait
{
    /**
     *   初期有効期間(sec)
     *
     * @var integer
     **/
    protected $defaultLifeTime = 0;
    
    /**
     *   {inherit}
     **/
    public function getMultiple($keys, $default = null)
    {
        $this->validateIterable($keys);
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
        $this->validateIterable($values);
        foreach ($values as $key => $value) {
            $result = $this->set($key, $value, $ttl);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }
    
    /**
     *   {inherit}
     **/
    public function deleteMultiple($keys)
    {
        $this->validateIterable($keys);
        $result = [];
        foreach ($keys as $key) {
            $result = $this->delete($key);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }
    
    /**
     *   {inherit}
     **/
    public function has($key)
    {
        return $this->get($key) !== null;
    }
    
    /**
     *   キー確認
     *
     * @param string $key
     * @thows InvalidArgumentException
     **/
    protected function validateKey($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "key must be type string:{$key}"
            );
        }
        return;
    }
    
    /**
     *   iterable確認
     *
     * @param iterable $keys
     * @thows InvalidArgumentException
     **/
    protected function validateIterable($keys)
    {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException(
                "key must be type iterable"
            );
        }
    }
    
    /**
     *   保存期限の変換
     *
     * @param  DateTImeInterface|int|null $ttl
     * @return int
     **/
    protected function parseExpire($ttl)
    {
        if (is_int($ttl)) {
            return $ttl;
        }
        
        if ($ttl === null) {
            return $this->defaultLifeTime;
        }
        
        if ($ttl instanceof DateInterval) {
            $now = new DateTimeImmutable();
            $expire = $now->add($ttl);
            return (int)$expire->format('U') - (int)$now->format('U');
        }
        
        throw new InvalidArgumentException(
            "ttl must be type int|DateInterval|null"
        );
    }
    
    /**
     *   setDefaultLifeTime
     *
     * @param  int $ttl
     * @return $this
     **/
    public function setDefaultLifeTime(int $ttl)
    {
        $this->defaultLifeTime = $ttl;
        return $this;
    }
}
