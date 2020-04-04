<?php
declare(type_stricts=1);

namespace Concerto\standard;

use Concerto\accessor\reflectable\{
    ReflectePropertyArrayExchangerTrait,
    ReflectePropertyTrait,
    ReflectePropertyTraitInterface
    
    
    ReflecteAttributeAccessTrait    //作る必要あり
        __get __set __isset __unset
    
};

class Post implements ReflectePropertyTraitInterface
{
    use ReflectePropertyTrait;
    use ReflectePropertyArrayExchangerTrait{
        toArray as public;
    }
    
    public function __construct(array $data)
    {
        $this->reflecteProperty()
            ->fromArray($data);
    }
}

