<?php

/**
*   ValueObject
*
*   @version 180708
*/

namespace Concerto\domain\common;

use Concerto\accessor\DataContainerValidatable;
use Concerto\accessor\ObjectImmutableTrait;
use Concerto\accessor\ObjectImmutableInterface;
use Concerto\Validate;

class ValueObject extends DataContainerValidatable implements ObjectImmutableInterface
{
    use ObjectImmutableTrait;
    
    /**
    *   construct
    *
    *   @param array
    **/
    public function __construct($param)
    {
        $this->fromArray(array $param);
    }
}
