<?php

/**
*   AttributeTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace Concerto\accessor;

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
    protected $dataContainer = [];

    /**
    *   {inherit}
    *
    */
    public function definedProperty(?string $name = null)
    {
        return isset($name) ?
            in_array($name, $this->propertyDefinitions) :
            $this->propertyDefinitions;
    }

    /**
    *   {inherit}
    *
    *   @return bool
    */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->dataContainer);
    }

    /**
    *   getDataFromContainer
    *
    *   @param string $name
    *   @return mixed
    */
    protected function getDataFromContainer(?string $name = null)
    {
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
    protected function setDataToContainer(string $name, $value): void
    {
        $this->checkPropertyName($name);
        $this->dataContainer[$name] = $value;
    }

    /**
    *   unsetDataFromContainer
    *
    *   @param string $name
    *   @return void
    */
    protected function unsetDataFromContainer(string $name): void
    {
        $this->checkPropertyName($name);
        unset($this->dataContainer[$name]);
    }

    /**
    *   checkPropertyName
    *
    *   @param string $name
    *   @return void
    */
    protected function checkPropertyName(string $name): void
    {
        if (!$this->definedProperty($name)) {
            throw new InvalidArgumentException(
                "not defined property:{$name}"
            );
        }
    }
}
