<?php

/**
*   TypeCastImmutableImplTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace candidate\accessor\impl;

use Concerto\accessor\impl\AttributeImmutableImplTrait;
use Concerto\accessor\TypeCastTrait;

trait TypeCastImmutableImplTrait
{
    use AttributeImmutableImplTrait;
    use TypeCastTrait;

    /**
    *   @inheritDoc
    *
    */
    public function __get(string $name)
    {
        $value = $this->getDataFromContainer($name);

        if ($this->hasGetCastType($name)) {
            return $this->toCastDataType(
                $this->getCastType($name),
                $value
            );
        }
        return $value;
    }
}
