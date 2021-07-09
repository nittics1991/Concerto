<?php

/**
*   ArrayDiffTrait
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array\stdarray;

trait ArrayDiffTrait
{
    
    
    
    
    
    /**
    *   diffAssoc
    *
    *   @param int|string|null $column_key
    *   @param ?bool $preserve_keys
    *   @return static
    */
    public function chunk(
        int|string|null $column_key,
        int|string|null $index_key,
    ): static {
        return new static(
            (array)array_column(
                $this->toArray(),
                $column_key,
                $index_key,
            )
        );
    }
    
    
    //multisort?=>sorttrait?
    
}
