<?php

/**
*   ArrayExchangerTrait
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\accessor\impl;

use Concerto\accessor\impl\AttributeImplTrait;

trait ArrayExchangerTrait
{
    use AttributeImplTrait;

    /**
    *    {inherit}
    *
    */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->definedProperty() as $name) {
            $result[$name] = $this->$name;
        }
        return $result;
    }

    /**
    *    配列から変換
    *
    *   @param mixed[] $dataset
    *   @return $this
    */
    public function fromArray(array $dataset)
    {
        foreach ($dataset as $key => $val) {
            $this->$key = $val;
        }
        return $this;
    }
}
