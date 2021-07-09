<?php

/**
*   ArrayCountTrait
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array\stdarray;

trait ArrayCountTrait
{
    /**
    *   count
    *
    *   @return int
    */
    public function count(
    ): int {
        return count($this->toArray())
    }
    
    /**
    *   countValues
    *
    *   @param array $keys
    *   @return static
    */
    public function countValues(
        array $values,
    ): static {
        return new static(
            (array)array_count_values(
                $this->toArray(),
            )
        );
    }
}
