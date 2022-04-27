<?php

/**
*   GetterInterface
*
*   @version 190516
*/

declare(strict_types=1);

namespace Concerto\accessor;

interface GetterInterface
{
    /**
    *   プロパティがgetterを持つ
    *
    *   @param string $propertyName
    *   @return bool
    */
    public function hasGetter(string $propertyName): bool;

    /**
    *   method名がgetterである
    *
    *   @param string $methodName
    *   @return bool
    */
    public function isGetterMethod($methodName): bool;
}
