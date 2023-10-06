<?php

/**
*   AttributeImplTrait
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\accessor\impl;

use Concerto\accessor\AttributeTrait;

trait AttributeImplTrait
{
    use AttributeTrait;

    /**
    *   @inheritDoc
    */
    public function __set(
        string $name,
        mixed $value
    ): void {
        $this->setDataToContainer($name, $value);
    }

    /**
    *   @inheritDoc
    */
    public function __get(
        string $name
    ): mixed {
        return $this->getDataFromContainer($name);
    }

    /**
    *   @inheritDoc
    */
    public function __isset(
        string $name
    ): bool {
        $val = $this->getDataFromContainer($name);
        return isset($val);
    }

    /**
    *   @inheritDoc
    */
    public function __unset(
        string $name
    ): void {
        $this->unsetDataFromContainer($name);
    }
}
