<?php

/**
*   AttributeTrait
*
*   @version 230112
*/

declare(strict_types=1);

namespace Concerto\accessor;

use DomainException;
use InvalidArgumentException;

trait AttributeTrait
{
    /**
    *   property name definition
    *
    *   @var array
    *   @warning implemention of the property is mondatory
    */
    // protected $propertyDefinitions = [];

    /**
    *   container for property data
    *
    *   @var mixed[]
    */
    protected array $dataContainer = [];

    /**
    *   プロパティ定義
    *
    *   @return string[]
    */
    public function getDefinedProperty(): array
    {
        $this->checkPropertyDefinitions();
        return $this->propertyDefinitions;
    }

    /**
    *   プロパティが定義されている
    *
    *   @param string $name
    *   @return bool
    */
    public function definedProperty(
        string $name
    ): bool {
        $this->checkPropertyDefinitions();
        return in_array($name, $this->propertyDefinitions);
    }

    /**
    *   has
    *
    *   @param string $name
    *   @return bool
    */
    public function has(
        string $name
    ): bool {
        return array_key_exists(
            $name,
            $this->dataContainer
        );
    }

    /**
    *   propertyDefinitions定義確認
    *
    *   @return void
    */
    protected function checkPropertyDefinitions(): void
    {
        if (
            !property_exists($this, 'propertyDefinitions') ||
            !is_array($this->propertyDefinitions)
        ) {
            throw new DomainException(
                "'propertyDefinitions' property definition required"
            );
        }
    }

    /**
    *   getDataFromContainer
    *
    *   @param string $name
    *   @return mixed
    */
    protected function getDataFromContainer(
        ?string $name = null
    ): mixed {
        if (!isset($name)) {
            return $this->dataContainer;
        }
        $this->checkPropertyName($name);
        return $this->dataContainer[$name] ?? null;
    }

    /**
    *   setDataToContainer
    *
    *   @param string $name
    *   @param mixed $value
    *   @return void
    */
    protected function setDataToContainer(
        string $name,
        mixed $value,
    ): void {
        $this->checkPropertyName($name);
        $this->dataContainer[$name] = $value;
    }

    /**
    *   unsetDataFromContainer
    *
    *   @param string $name
    *   @return void
    */
    protected function unsetDataFromContainer(
        string $name
    ): void {
        $this->checkPropertyName($name);
        unset($this->dataContainer[$name]);
    }

    /**
    *   checkPropertyName
    *
    *   @param string $name
    *   @return void
    */
    protected function checkPropertyName(
        string $name
    ): void {
        if (!$this->definedProperty($name)) {
            throw new InvalidArgumentException(
                "not defined property:{$name}"
            );
        }
    }
}
