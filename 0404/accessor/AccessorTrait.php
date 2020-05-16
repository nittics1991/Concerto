<?php

/**
*   AccessorTrait
*
*   @version 200516
*/

declare(type_stricts=1);

namespace Concerto\accessor;

use BadMethodCallException;
use ReflectionMethod;

trait AccessorTrait
{
    /**
    *   getters
    *
    *   @var string[] ['propertyName1', ...]
    */
    private array $getters = [];
     
    /**
    *   setters
    *
    *   @var string[] ['propertyName1', ...]
    */
    private array $setters = [];
    
    /**
    *   hasAccessor
    *
    *   @param string $method_name
    *   @param string $type get|set
    *   @return bool
    */
    private function hasAccessor(
        string $method_name,
        string $type
    ): bool {
        if (!method_exists($method_name)) {
            return false;
        }
        
        $method = new ReflectionMethod($method_name);
        
        if (!$method->isPublic()) {
            return false;
        }
        
        $propery_name = mb_convert_case(
            (string)mb_ereg_replace("^{$type}", '', $method_name),
            MB_CASE_LOWER
        );
        
        if (!$this->has($propery_name)) {
            return false;
        }
        
        $accessor_name = "{$type}ters";
        return in_array($propery_name, $this->$accessor_name);
    }
    
    /**
    *   hasGetter
    *
    *   @param string $method_name
    *   @return bool
    */
    private function hasGetter(string $method_name): bool
    {
        return $this->hasAccessoor($method_name, 'get');
    }
    
    /**
    *   hasSetter
    *
    *   @param string $method_name
    *   @return bool
    */
    private function hasSetter(string $method_name): bool
    {
        return $this->hasAccessoor($method_name, 'set');
    }
    
    /**
    *   callAccessor
    *
    *   @param string $name
    *   @param array $arguments
    *   @return mixed
    */
    private function callAccessor(string $name, array $arguments = [])
    {
        if ($this->hasGetter($name)) {
            return $this->$name();
        }
        
        if ($this->hasGetter($name)
            && $this->hasSetter($name)
        ) {
            return call_user_func_array([$this, $name], $arguments));
        }
        
        throw new BadMethodCallException(
            "not accesed method:{$name}"
        );
    }
    
    /**
    *   {inherit}
    *
    */
    public function __call(string $name, array $arguments)
    {
        return $this->callAccessor($name, $arguments);
    }
}
