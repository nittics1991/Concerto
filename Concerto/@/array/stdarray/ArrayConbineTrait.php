<?php

/**
*   ArrayConbineTrait
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array\stdarray;

trait ArrayConbineTrait
{
    /**
    *   combineKeys
    *
    *   @param array $keys
    *   @return static
    */
    public function combineKeys(
        array $keys,
    ): static {
        return new static(
            (array)array_combine(
                $keys,
                $this->toArray(),
            )
        );
    }
    
    /**
    *   combineKeysUseKey
    *
    *   @param array $keys
    *   @return static
    */
    public function combineKeysUseKey(
        array $keys,
    ): static {
        return new static(
            (array)array_combine(
                $keys,
                array_keys($this->toArray()),
            )
        );
    }
    
    /**
    *   combineValues
    *
    *   @param array $keys
    *   @return static
    */
    public function combineValues(
        array $values,
    ): static {
        return new static(
            (array)array_combine(
                array_keys($this->toArray()),
                $values,
            )
        );
    }
    
    /**
    *   combineValuesUseValue
    *
    *   @param array $keys
    *   @return static
    */
    public function combineValuesUseValue(
        array $values,
    ): static {
        return new static(
            (array)array_combine(
                $this->toArray(),
                $values,
            )
        );
    }
}
