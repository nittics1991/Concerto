<?php

/**
*   AttributeImplTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace Concerto\accessor\impl;

use Concerto\accessor\AttributeTrait;

trait AttributeImplTrait
{
    use AttributeTrait;

    /**
    *   {inherit}
    *
    */
    public function __set(string $name, $value): void
    {
        $this->setDataToContainer($name, $value);
    }

    /**
    *   {inherit}
    *
    */
    public function __get(string $name)
    {
        return $this->getDataFromContainer($name);
    }

    /**
    *   {inherit}
    *
    */
    public function __isset(string $name): bool
    {
        $val = $this->getDataFromContainer($name);
        return isset($val);
    }

    /**
    *   {inherit}
    *
    */
    public function __unset(string $name): void
    {
        $this->unsetDataFromContainer($name);
    }
}
