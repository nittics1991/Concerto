<?php

/**
*   AttributeImmutableImplTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace Concerto\accessor\impl;

use Concerto\accessor\AttributeImmutableTrait;
use Concerto\accessor\impl\AttributeImplTrait;

trait AttributeImmutableImplTrait
{
    use AttributeImplTrait, AttributeImmutableTrait {
            AttributeImmutableTrait::__set insteadof AttributeImplTrait;
            AttributeImmutableTrait::__unset insteadof AttributeImplTrait;
    }
}
