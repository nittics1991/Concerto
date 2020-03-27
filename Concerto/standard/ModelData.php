<?php

/**
*   データベーステーブルデータ
*
*   @version 190524
*/

namespace Concerto\standard;

use DateTime;
use InvalidArgumentException;
use Concerto\standard\DataContainerValidatable;
use Concerto\standard\DataModelInterface;

class ModelData extends DataContainerValidatable implements
    DataModelInterface
{
    /**
    *   gettype判定用定数
    *
    */
    public const BOOLEAN = 'boolean';
    public const INTEGER = 'integer';
    public const FLOAT = 'double';
    public const DOUBLE = 'double';
    public const STRING = 'string';
    public const DATETIME = 'datetime';
    
    /**
    *   カラム情報(overwrite)
    *
    *   @var array
    *
    *   @example array('bool_data' => parent::BOOLEAN,
    *                               'int_data' => parent::INTEGER)
    */
    protected static $schema = [];
    
    /**
    *   カラム名エイリアス(overwrite)
    *
    *   @var array
    *
    *   @example array('nm_tanto' => 'tanto_code')
    */
    protected static $alias = [];
    
    /**
    *   __construct
    *
    *   @param array $params
    */
    public function __construct(array $params = [])
    {
        $this->fromArray($params);
    }
    
    /**
    *   {inherit}
    *
    *   @throws InvalidArgumentException
    */
    public function offsetGet($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        
        if (array_key_exists($key, static::$schema)) {
            return null;
        }
        
        if (array_key_exists($key, static::$alias)) {
            $name = static::$alias[$key];
            return $this->offsetGet($name);
        }
        throw new InvalidArgumentException("no property called:{$key}");
    }
    
    /**
    *   {inherit}
    *
    *   @throws InvalidArgumentException
    */
    public function offsetSet($key, $val)
    {
        if (array_key_exists($key, static::$alias)) {
            $name = static::$alias[$key];
            $this->__set($name, $val);
            return;
        }
        
        if (!array_key_exists($key, static::$schema)) {
            throw new InvalidArgumentException("no property called:{$key}");
        }
        
        $schema = static::$schema[$key];
        $type = gettype($val);
        
        if ($val === null) {
            $this->data[$key] = $val;
            return;
        }
            
        if ($schema === self::DATETIME) {
            if ($val instanceof DateTime) {
                $this->data[$key] = $val;
            } else {
                $this->data[$key] = new DateTime($val);
            }
            return;
        }
        
        if ($type === $schema) {
            $this->data[$key] = $val;
            return;
        }
        
        switch ($schema) {
            case self::BOOLEAN:
                $this->data[$key] = (bool)$val;
                return;
            case self::INTEGER:
                $this->data[$key] = (int)$val;
                return;
            case self::FLOAT:
                $this->data[$key] = (float)$val;
                return;
            case self::DOUBLE:
                $this->data[$key] = (double)$val;
                return;
            case self::STRING:
                $this->data[$key] = (string)$val;
                return;
            default:
                $this->data[$key] = (string)$val;
                return;
        }
        $this->data[$key];
        return;
    }
    
    /**
    *   {inherit}
    *
    */
    public function offsetExists($key)
    {
        if (array_key_exists($key, static::$alias)) {
            $name = static::$alias[$key];
            return isset($this->$name);
        }
        return isset($this->data[$key]);
    }
    
    /**
    *   カラム情報
    *
    *   @param ?string $key
    *   @return array|string
    *   @throws InvalidArgumentException
    */
    public function getInfo($key = null)
    {
        if (is_null($key)) {
            return static::$schema;
        }
        
        if (array_key_exists($key, static::$schema)) {
            return static::$schema[$key];
        }
        
        if (array_key_exists($key, static::$alias)) {
            $name = static::$alias[$key];
            return $this->getInfo($name);
        }
        throw new InvalidArgumentException("no property called:{$key}");
    }
    
    /**
    *   バリデート
    *
    *   @return bool
    */
    public function isValid()
    {
        $this->valid = [];
        
        if (empty($this->data)) {
            return true;
        }
        
        $result = true;
        
        foreach ($this->data as $key => $val) {
            if (!array_key_exists($key, static::$schema)) {
                $this->valid[$key][] = 'does not exists in schema';
                $result = false;
            }
            
            $result = $this->validCom((string)$key, $val) && $result;
            $result = $this->validCustom((string)$key, $val) && $result;
        }
        return $result;
    }
}
