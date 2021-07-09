<?php

/**
*   MultiTenantTrait
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array\stdarray;

trait MultiTenantTraitAAAAAAAAAaa
{
    /**
    *   chunk
    *
    *   @param int $length
    *   @param ?bool $preserve_keys
    *   @return static
    */
    public function chunk(
        int $length,
        ?bool $preserve_keys,
    ): static {
        return new static(
            (array)array_chunk(
                $this->toArray(),
                $length
                $preserve_keys?? false,
            )
        );
    }
}
