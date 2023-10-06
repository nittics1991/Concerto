<?php

/**
*   GetterTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace candidate\accessor;

use BadMethodCallException;

trait GetterTrait
{
    /**
    *   property name of Getter
    *
    *   @var array
    *   @warning implemention of the property is mondatory
    */
    // protected $getterDefinitions = [];

    /**
    *   @inheritDoc
    *
    */
    public function hasGetter(string $propertyName): bool
    {
        return in_array($propertyName, $this->getterDefinitions);
    }

    /**
    *   @inheritDoc
    *
    */
    public function isGetterMethod($methodName): bool
    {
        return mb_substr($methodName, 0, 3) === 'get' &&
            $this->hasGetter(
                lcfirst(mb_substr($methodName, 3))
            );
    }

    /**
    *   Getter
    *
    *   @param string $name
    *   @return mixtd
    */
    protected function getter(string $name)
    {
        if (!$this->isGetterMethod($name)) {
            throw new BadMethodCallException(
                "not defined method:{$name}"
            );
        }
        $name = lcfirst(mb_substr($name, 3));
        return $this->$name;
    }

    /**
    *   calledFromGetter
    *
    *   @return bool
    */
    protected function calledFromGetter(): bool
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
        return $traces[2]['function'] === 'getter';
    }

    /**
    *   ゲッターの書式例
    *
    *   @param mixed $value
    */
    //public function getPROPERTYNAME()
}
