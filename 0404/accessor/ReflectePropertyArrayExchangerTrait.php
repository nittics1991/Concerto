<?php
declare(type_stricts=1);

namespace Concerto\accessor\reflectable;

use InvalidArgumentException;
use Concerto\accessor\reflectable\ReflectePropertyTraitInterface;

trait ReflectePropertyArrayExchangerTrait implements
    ReflectePropertyTraitInterface
{
    /**
    *   fromArray
    *
    *   @param array $data
    */
    public function fromArray(array $data)
    {
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
    public function toArray():array
    {
        return array_map(
            function($name) {
                return $this->$name;
            },
            array_keys($this->properties)
        );
    }
}
