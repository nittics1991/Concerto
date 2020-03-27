<?php

/**
*   データコンテナ
*
*   @version 190524
*/

namespace Concerto\standard;

use Concerto\standard\ArrayAccessObject;
use InvalidArgumentException;

abstract class DataContainer extends ArrayAccessObject
{
    /**
    *   カラム情報(overwrite)
    *
    *   @var array
    *   @example array('bool_data', 'int_data')
    */
    protected static $schema = [];
    
    /**
    *   {inherit}
    *
    */
    public function offsetGet($key)
    {
        if ($this->offsetExists($key)) {
            return $this->data[$key];
        }
        
        if (in_array($key, static::$schema)) {
            return null;
        }
        throw new InvalidArgumentException("no property called:{$key}");
    }
    
    /**
    *   {inherit}
    *
    */
    public function offsetSet($key, $val)
    {
        if (!in_array($key, static::$schema)) {
            throw new InvalidArgumentException("no property called:{$key}");
        }
        $this->data[$key] = $val;
    }
    
    /**
    *   カラム情報
    *
    *   @param ?string $key
    *   @return mixed
    */
    public function getInfo(?string $key = null)
    {
        if (is_null($key)) {
            return static::$schema;
        }
        
        if (($pos = array_search($key, static::$schema)) !== false) {
            return static::$schema[$pos];
        }
        throw new InvalidArgumentException("no property called:{$key}");
    }
}
