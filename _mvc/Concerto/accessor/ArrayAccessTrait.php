<?php

/**
*   ArrayAccessTrait
*
*   @version 220124
*/

declare(strict_types=1);

namespace Concerto\accessor;

trait ArrayAccessTrait
{
    /**
    *    {inherit}
    *
    */
    public function offsetExists(
        mixed $offset
    ): bool {
        return isset($this->$offset);
    }

    /**
    *    {inherit}
    *
    */
    public function offsetGet(
        mixed $offset
    ): mixed {
        return $this->$offset;
    }

    /**
    *    {inherit}
    *
    */
    public function offsetSet(
        mixed $offset,
        mixed $value
    ): void {
        $this->$offset = $value;
    }

    /**
    *    {inherit}
    *
    */
    public function offsetUnset(
        mixed $offset
    ): void {
        unset($this->$offset);
    }
}
