<?php

/**
*  AttributeImmutableTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace Concerto\accessor;

use BadMethodCallException;

trait AttributeImmutableTrait
{
    /**
    *   {inherit}
    *
    */
    public function __set(string $name, $value): void
    {
        $this->immutableException($name);
    }

    /**
    *   {inherit}
    *
    */
    public function __unset(string $name): void
    {
        $this->immutableException($name);
    }

    /**
    *   exception of write method
    *
    *   @param string $name
    */
    protected function immutableException(string $name): void
    {
        throw new BadMethodCallException(
            "this class is Immutable:{$name}"
        );
    }
}
