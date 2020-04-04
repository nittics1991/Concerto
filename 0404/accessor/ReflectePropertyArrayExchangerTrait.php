<?php
declare(type_stricts=1);

namespace Concerto\accessor\reflectable;

use InvalidArgumentException;

trait ReflectePropertyArrayExchangerTrait
{
    /**
    *   fromArray
    *
    *   @param array $data
    */
    private function fromArray(array $data)
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        foreach($data as $name => $val) {
            if (!array_key_exists($name, $this->properties)) {
                throw new InvalidArgumentException(
                    "not defined property:{$name}"
                );
            }
            
            if (($this->properties[$name])->isPrivate()) {
                throw new InvalidArgumentException(
                    "invalid visibility:{$name}"
                );
            }
            $this->$name = $val;
        }
    }
        
    /**
    *   toArray
    *
    *   @return array
    */
    private function toArray():array
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        return array_map(
            function($name) {
                return $this->$name;
            },
            array_keys($this->properties)
        );
    }
}
