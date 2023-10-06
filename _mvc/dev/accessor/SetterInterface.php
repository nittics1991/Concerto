<?php

/**
*   SetterInterface
*
*   @version 190516
*/

declare(strict_types=1);

namespace candidate\accessor;

interface SetterInterface
{
    /**
    *   プロパティがsetterを持つ
    *
    *   @param string $propertyName
    *   @return bool
    */
    public function hasSetter(string $propertyName): bool;

    /**
    *   method名がsetterである
    *
    *   @param string $methodName
    *   @return bool
    */
    public function isSetterMethod($methodName): bool;
}
