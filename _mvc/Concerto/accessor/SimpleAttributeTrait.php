<?php

/**
*   SimpleAttributeTrait
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\accessor;

trait SimpleAttributeTrait
{
    /**
    *   @var mixed[]
    */
    protected array $property_container = [];

    /**
    *   @inheritDoc
    */
    public function __isset(
        string $name,
    ): bool {
        return array_key_exists(
            $name,
            $this->property_container,
        );
    }

    /**
    *   @inheritDoc
    */
    public function __get(
        string $name,
    ): mixed {
        return $this->__isset($name) ?
            $this->property_container[$name] : null;
    }

    /**
    *   @inheritDoc
    */
    public function __set(
        string $name,
        mixed $value,
    ): void {
        $this->property_container[$name] = $value;
    }

    /**
    *   @inheritDoc
    */
    public function __unset(
        string $name,
    ): void {
        unset($this->property_container[$name]);
    }
}
