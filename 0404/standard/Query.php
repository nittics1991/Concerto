<?php

/**
*   Query
*
*   @version 200405
*/

declare(type_stricts=1);

namespace Concerto\standard;

use ArrayAccess;
use Concerto\accessor\{
    ArrayAccessTrait,
    ReflectePropertyTrait,
    ReflectePropertyTraitInterface
};

class Query implements ArrayAccess,
    ReflectePropertyTraitInterface
{
    use ReflectePropertyTrait;
    use ArrayAccessTrait;
    
    public function __construct(array $data)
    {
        $this->reflecteProperty()
            ->fromArray($data);
    }
    
    /**
    *   buryProperties
    *
    *   @param $data
    *   @return $this 
    */
    public function buryProperties(array $data)
    {
        foreach($data as $name => $val) {
            if (array_key_exists($name, $this->properties) &&
                !isset($this->properties[$name]
            ) {
                $this->properties[$name] = $val;
            }
        }
        return $this;
    }
}
