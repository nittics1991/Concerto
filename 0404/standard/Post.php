<?php

/**
*   Post
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

class Post implements ArrayAccess,
    ReflectePropertyTraitInterface
{
    use ReflectePropertyTrait;
    use ArrayAccessTrait;
    
    public function __construct(array $data)
    {
        $this->reflecteProperty()
            ->fromArray($data);
    }
}

