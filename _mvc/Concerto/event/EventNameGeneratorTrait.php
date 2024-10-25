<?php

/**
*   EventNameGeneratorTrait
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

use RuntimeException;

trait EventNameGeneratorTrait
{
    /**
    *   {inheritDoc}
    */
    public function generateEventName(
        string $suffix = '',
    ): string {
        $stackTrace = debug_backtrace();

        if (
            !isset($stackTrace[1]) ||
            !isset($stackTrace[1]['class'])
        ) {
            throw new RuntimeException(
                "must be called metthod"
            );
        }

        return $stackTrace[1]['class'] .
            '::' .
            $stackTrace[1]['function'] .
            '.' .
            $suffix;
    }
}
