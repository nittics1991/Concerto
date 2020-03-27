<?php

/**
*   ArrayAccessObject
*
*   @version 190524
*/

namespace Concerto\standard;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Countable;
use InvalidArgumentException;

class ArrayAccessObject implements ArrayAccess, IteratorAggregate, Countable
{
    /**
    *   データコンテナ
    *
    *   @var array
    */
    protected $data = [];
    
    /**
    *   {inherit}
    *
    */
    public function __get(string $key)
    {
        return $this->offsetGet($key);
    }
    
    /**
    *   {inherit}
    *
    */
    public function __set(string $key, $val): void
    {
        $this->offsetSet($key, $val);
    }
    
    /**
    *   {inherit}
    *
    */
    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }
    
    /**
    *   {inherit}
    *
    */
    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }
    
    /**
    *   {inherit}
    *
    */
    public function offsetGet($key)
    {
        return (isset($this->data[$key])) ?   $this->data[$key] : null;
    }
    
    /**
    *   {inherit}
    *
    */
    public function offsetSet($key, $val)
    {
        $this->data[$key] = $val;
    }
    
    /**
    *   {inherit}
    *
    */
    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }
    
    /**
    *   {inherit}
    *
    */
    public function offsetUnset($key): void
    {
        unset($this->data[$key]);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function count()
    {
        return count($this->data);
    }
    
    /**
    *   データ初期化
    *
    **/
    public function unsetAll()
    {
        $this->data = [];
    }
    
    /**
    *   一括入力
    *
    *   @param array $array [id => data]
    *   @return object $this
    */
    public function fromArray(array $array)
    {
        if (!is_array($array)) {
            throw new InvalidArgumentException("only array");
        }
        
        foreach ($array as $key => $val) {
            $this[$key] = $val;
        }
        return $this;
    }
    
    /**
    *   一括出力
    *
    *   @return array
    */
    public function toArray(): array
    {
        return $this->data;
    }
    
    /**
    *   empty
    *
    *   @param ?string $key
    *   @return bool
    **/
    public function isEmpty(string $key = null): bool
    {
        if (!is_null($key)) {
            return (isset($this->data[$key])) ?  empty($this->data[$key]) : true;
        }
        
        foreach ((array)$this->data as $val) {
            if (!empty($val)) {
                return false;
            }
        }
        return true;
    }
    
    /**
    *   NULL
    *
    *   @param string $key
    *   @return bool
    **/
    public function isNull(string $key = null): bool
    {
        if (!is_null($key)) {
            return !isset($this->data[$key]);
        }
        
        foreach ((array)$this->data as $val) {
            if (!is_null($val)) {
                return false;
            }
        }
        return true;
    }
}
