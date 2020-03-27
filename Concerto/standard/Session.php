<?php

/**
*   Session
*
*   @version 190523
*/

namespace Concerto\standard;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class Session implements ArrayAccess, IteratorAggregate, Countable
{
    /**
    *   データコンテナ
    *
    *   @var array
    **/
    protected $data = [];
    
    /**
    *   空間名
    *
    *   @var ?string
    **/
    protected $namespace;
    
    /**
    *   状態
    *
    *   @var bool
    **/
    protected $isStarted;
    
    /**
    *   __construct
    *
    *   @param ?string $namespace SESSION空間名
    **/
    public function __construct(?string $namespace = null)
    {
        $this->namespace = $namespace;
    }
    
    /**
    *   {inherit}
    **/
    public function __get(string $key)
    {
        $this->start();
        $result = (isset($this->data[$key])) ?
            $this->data[$key] : null;
        $this->commit();
        return $result;
    }
    
    /**
    *   {inherit}
    **/
    public function __set(string $key, $val): void
    {
        $this->start();
        $this->data[$key] = $val;
        $this->commit();
    }
    
    /**
    *   {inherit}
    **/
    public function __isset(string $key): bool
    {
        $this->start();
        $result = isset($this->data[$key]);
        $this->commit();
        return $result;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __unset(string $key): void
    {
        $this->start();
        $this->data[$key] = null;
        $this->commit();
    }
    
    /**
    *   start
    *
    *   @return bool
    **/
    public function start(): bool
    {
        if ($this->isStarted) {
            return true;
        }
        
        $result = false;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $result = session_start();
        }
        
        if ($result || !$this->isStarted) {
            if (is_null($this->namespace)) {
                $this->data = &$_SESSION;
            } else {
                $this->data = &$_SESSION[$this->namespace];
            }
            $this->isStarted = true;
        }
        return $result;
    }
    
    /**
    *   write and close
    *
    **/
    public function commit()
    {
        session_write_close();
        $this->isStarted = false;
    }
    
    /**
    *   ID変更
    *
    **/
    public function changeID()
    {
        $this->start();
        session_regenerate_id(true);
        $this->commit();
    }
    
    /**
    *   {inherit}
    **/
    public function offsetGet($key)
    {
        return $this->__get($key);
    }
    
    /**
    *   {inherit}
    **/
    public function offsetSet($key, $val): void
    {
        $this->__set($key, $val);
    }
    
    /**
    *   {inherit}
    **/
    public function offsetExists($key): bool
    {
        $this->__isset($key);
    }
    
    /**
    *   {inherit}
    **/
    public function offsetUnset($key): void
    {
        $this->__unset($key);
    }
    
    /**
    *   {inherit}
    **/
    public function getIterator()
    {
        $this->start();
        $result = new ArrayIterator($this->data);
        $this->commit();
        return $result;
    }
    
    /**
    *   {inherit}
    **/
    public function count()
    {
        $this->start();
        $result = count($this->data);
        $this->commit();
        return $result;
    }
    
    /**
    *   {inherit}
    **/
    public function unsetAll()
    {
        $this->start();
        $this->data = [];
        $this->commit();
    }
    
    /**
    *   {inherit}
    **/
    public function fromArray(array $array)
    {
        $this->start();
        $this->data = $array;
        $this->commit();
        return;
    }
    
    /**
    *   {inherit}
    **/
    public function toArray()
    {
        $this->start();
        $result = $this->data;
        $this->commit();
        return $result;
    }
    
    /**
    *   {inherit}
    **/
    public function isEmpty($key = null)
    {
        $this->start();
        
        if (!is_null($key)) {
            $result = (isset($this->data[$key])) ?
                empty($this->data[$key]) : true;
        } elseif (empty($this->data)) {
            $result = true;
        } else {
            $result = true;
            foreach ((array)$this->data as $val) {
                if (!empty($val)) {
                    $result = false;
                    break;
                }
            }
        }
        $this->commit();
        return $result;
    }
    
    /**
    *   {inherit}
    **/
    public function isNull($key = null)
    {
        $this->start();
        
        if (!is_null($key)) {
            $result = !isset($this->data[$key]);
        } elseif ($this->data === null) {
            $result = true;
        } else {
            $result = true;
            foreach ((array)$this->data as $val) {
                if (!is_null($val)) {
                    $result = false;
                    break;
                }
            }
        }
        $this->commit();
        return $result;
    }
}
