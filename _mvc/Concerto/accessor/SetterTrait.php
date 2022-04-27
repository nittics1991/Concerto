<?php

/**
*   SetterTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace Concerto\accessor;

use BadMethodCallException;

trait SetterTrait
{
    /**
    *   property name of setter
    *
    *   @var array
    *   @warning implemention of the property is mondatory
    */
    // protected $setterDefinitions = [];

    /**
    *   {inherit}
    *
    */
    public function hasSetter(string $propertyName): bool
    {
        return in_array($propertyName, $this->setterDefinitions);
    }

    /**
    *   {inherit}
    *
    */
    public function isSetterMethod($methodName): bool
    {
        return mb_substr($methodName, 0, 3) === 'set' &&
            $this->hasSetter(
                lcfirst(mb_substr($methodName, 3))
            );
    }

    /**
    *   setter
    *
    *   @param string $name
    *   @param array $arguments
    *   @return void
    */
    protected function setter(string $name, array $arguments): void
    {
        if (!$this->isSetterMethod($name)) {
            throw new BadMethodCallException(
                "not defined method:{$name}"
            );
        }
        $name = lcfirst(mb_substr($name, 3));
        $this->$name = $arguments[0];
    }

    /**
    *   calledFromSetter
    *
    *   @return bool
    */
    protected function calledFromSetter(): bool
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);

        return isset($traces[2]['function']) ?
            $traces[2]['function'] === 'setter' : false;
    }

    /**
    *   セッターの書式例
    *
    *   @param mixed $value
    */
    //public function setPROPERTYNAME($value)
}
