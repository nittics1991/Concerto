<?php

//Query要確認


/**
*   Post
*
*   @version 200516
*/

declare(type_stricts=1);

namespace Concerto\standard;

use Concerto\accessor\{
    ReflectePropertyTrait,
    ReflectePropertyTraitInterface
};

class Post implements ArrayAccess,
    ReflectePropertyTraitInterface
{
    use ReflectePropertyTrait;
    
    public function __construct(array $data)
    {
        $this->fromArray($data);
    }
}
