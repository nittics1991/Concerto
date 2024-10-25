<?php

/**
*   DefineMethodTrait
*
*   @version 210716
*/

declare(strict_types=1);

namespace Concerto\wrapper\array;

use Concerto\wrapper\array\StandardArrayObject;

trait DefineMethodTrait
{
    /**
    *   resolveDataset
    *
    *   @param StandardArrayObject|iterable $keys
    *   @return array
    */
    protected function resolveDataset(
        StandardArrayObject|iterable $dataset
    ): array{
        if ($dataset instanceof StandardArrayObject) {
            return $dataset->toArray();
        }
        
        if (is_array($dataset)) {
            return $dataset;
        }
        
        return iterator_to_array($dataset);
    }
    
    /**
    *   combineKeyUseKey
    *
    *   @param StandardArrayObject|iterable $keys
    *   @return static
    */
    public function combineKeyUseKey(
        StandardArrayObject|iterable $keys
    ): static{
        return new static(array_combine(
            $this->resolveDataset($keys),
            array_keys($this->toArray()),
        ));
    }
    
    /**
    *   combineKeyUseValue
    *
    *   @param StandardArrayObject|iterable $keys
    *   @return static
    */
    public function combineKeyUseValue(
        StandardArrayObject|iterable $keys
    ): static{
        return new static(array_combine(
            $this->resolveDataset($keys),
            array_values($this->toArray()),
        ));
    }
    
    /**
    *   combineValueUseKey
    *
    *   @param StandardArrayObject|iterable $values
    *   @return static
    */
    public function combineValueUseKey(
        StandardArrayObject|iterable $values
    ): static{
        return new static(array_combine(
            array_keys($this->toArray()),
            $this->resolveDataset($values),
        ));
    }
    
    /**
    *   combineValueUseValue
    *
    *   @param StandardArrayObject|iterable $values
    *   @return static
    */
    public function combineValueUseValue(
        StandardArrayObject|iterable $values
    ): static{
        return new static(array_combine(
            array_values($this->toArray()),
            $this->resolveDataset($values),
        ));
    }
}
