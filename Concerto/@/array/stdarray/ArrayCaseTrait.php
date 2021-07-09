<?php

/**
*   ArrayCaseTrait
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array\stdarray;

trait ArrayCaseTrait
{
    /**
    *   changeKeyCase
    *
    *   @param ?int $case
    *   @return static
    */
    public function changeKeyCase(
        ?int $case,
    ): static
    {
        return new static(
            array_change_key_case(
                $this->toArray(),
                $case?? CASE_LOWER,
            )
        );
    }
    
    /**
    *   changeKeyLowerCase
    *
    *   @return static
    */
    public function changeKeyLowerCase(
    ): static {
        return $this->changeKeyCase(
            CASE_LOWER,
        );
    }
    
    /**
    *   changeKeyUpperCase
    *
    *   @return static
    */
    public function changeKeyUpperCase(
    ): static {
        return $this->changeKeyCase(
            CASE_UPPER,
        );
    }
}
