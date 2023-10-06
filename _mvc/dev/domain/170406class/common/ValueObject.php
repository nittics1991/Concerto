<?php

/**
*   ValueObject
*
*   @version 180708
*/

namespace dev\domain\common;

use dev\accessor\DataContainerValidatable;
use dev\accessor\ObjectImmutableTrait;
use dev\accessor\ObjectImmutableInterface;
use dev\Validate;

class ValueObject extends DataContainerValidatable implements ObjectImmutableInterface
{
    use ObjectImmutableTrait;

    /**
    *   construct
    *
    *   @param array
    */
    public function __construct($param)
    {
        $this->fromArray(array $param);
    }
}
