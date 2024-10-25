<?php

/**
*   EventNameBuilder
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

class EventNameBuilder
{
    /**
    *   build
    *
    *   @param string $className
    *   @param string $methodName
    *   @param string $suffix
    *   @return string
    */
    public static function build(
        string $className,
        string $methodName,
        string $suffix = '',
    ): string {
        return $className .
            '::' .
            $methodName .
            '.' .
            $suffix;
    }
}
