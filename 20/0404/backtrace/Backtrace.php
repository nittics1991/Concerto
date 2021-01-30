<?php

/**
*   Backtrace
*
*   @version 200405
*/

declare(type_stricts=1);

namespace Concerto\trace;

class Backtrace
{
    protected array $originalData;
    
    /**
    *   __construct
    *
    *   @param array $trace
    */
    public function __construct(array $trace)
    {
        $this->originalData = $trace;
    }
    
    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array
    {
        return $this->originalData;
    }
    
    /**
    *   {inherit}
    *
    */
    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->originalData)) {
            throw InvalidArgumentException(
                "not defined:{$name}"
            );
        }
        return $this->originalData[$name];
    }
    
    /**
    *   {inherit}
    *
    */
    public function __isset(string $name)
    {
        return array_key_exists($name, $this->originalData);
    }
    
    /**
    *   has
    *
    *   @param strint $name
    *   @return bool  
    */
    public function has(string $name):bool
    {
        return isset($tihs->$name);
    }
    
    /**
    *   isClosure
    *
    *   @return bool  
    */
    public function isClosure():bool
    {
        return $this->has('function') &&
            $this->function === '{closure}';
    }
    
    /**
    *   isObject
    *
    *   @return bool  
    */
    public function isObject():bool
    {
        return $this->has('object') ||
            $this->has('class');
    }
    
    /**
    *   isClass
    *
    *   @return bool  
    */
    public function isClass():bool
    {
        return $this->has('class');
    }
    
    /**
    *   isInstance
    *
    *   @return bool  
    */
    public function isInstance():bool
    {
        return $this->isObject() &&
            $this->type === '->'
    }
    
    /**
    *   isStatic
    *
    *   @return bool  
    */
    public function isStatic():bool
    {
        return $this->isObject() &&
            $this->type === '::'
    }
    
    /**
    *   countArguments
    *
    *   @return int  
    */
    public function countArguments():int
    {
        return count($this->args);
    }
}
