<?php

/**
*   CheckMethodTrait
*
*   @version 210726
*/

declare(strict_types=1);

namespace candidate\wrapper\array\extend;

trait CheckMethodTrait
{
    /**
    *  any
    *
    *   @param mixed $value
    *   @return bool
    */
    public function any(
        mixed $value
    ): bool {
        foreach ($this->toArray() as $current) {
            if ($current === $value) {
                return true;
            }
        }
        return false;
    }

    /**
    *  every
    *
    *   @param mixed $value
    *   @return bool
    */
    public function every(
        mixed $value
    ): bool {
        foreach ($this->toArray() as $current) {
            if ($current !== $value) {
                return false;
            }
        }
        return true;
    }

    /**
    *  isEmpty
    *
    *   @return bool
    */
    public function isEmpty(): bool
    {
        return $this->toArray() === [];
    }
}
