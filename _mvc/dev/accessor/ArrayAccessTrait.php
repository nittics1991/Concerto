<?php

/**
*   ArrayAccessTrait
*
*   @version 220124
*/

declare(strict_types=1);

namespace candidate\accessor;

trait ArrayAccessTrait
{
    /**
    *    @inheritDoc
    *
    */
    public function offsetExists(
        mixed $offset
    ): bool {
        return isset($this->$offset);
    }

    /**
    *    @inheritDoc
    *
    */
    public function offsetGet(
        mixed $offset
    ): mixed {
        return $this->$offset;
    }

    /**
    *    @inheritDoc
    *
    */
    public function offsetSet(
        mixed $offset,
        mixed $value
    ): void {
        $this->$offset = $value;
    }

    /**
    *    @inheritDoc
    *
    */
    public function offsetUnset(
        mixed $offset
    ): void {
        unset($this->$offset);
    }
}
