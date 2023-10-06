<?php

/**
*   ArrayExchangerTrait
*
*   @version 221221
*/

declare(strict_types=1);

namespace Concerto\accessor\impl;

use Concerto\accessor\impl\AttributeImplTrait;

trait ArrayExchangerTrait
{
    use AttributeImplTrait;

    /**
    *   配列へ変換
    *
    *   @return mixed[]
    */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->getDefinedProperty() as $name) {
            $result[$name] = $this->$name;
        }
        return $result;
    }

    /**
    *    配列から変換
    *
    *   @param mixed[] $dataset
    *   @return static
    */
    public function fromArray(
        array $dataset
    ): static {
        foreach ($dataset as $key => $val) {
            $this->$key = $val;
        }
        return $this;
    }
}
