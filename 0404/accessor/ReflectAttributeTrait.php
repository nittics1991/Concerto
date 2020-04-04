<?php

/**
*   ReflectAttributeTrait
*
*   @version aaa
**/

declare(strict_types=1);

namespace Concerto\accessor\reflectable;

use LogicException;

trait ReflectAttributeTrait
{
    /**
    *   {inherit}
    *
    **/
    public function __get(string $name)
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        if (!array_key_exists($name, $this->properties)) {
           throw new LogicException(
                "not defined property:{$name}"
            );
        }
        return $this->$name;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __isset(string $name): bool
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        return array_key_exists($name, $this->properties);
    }
}
