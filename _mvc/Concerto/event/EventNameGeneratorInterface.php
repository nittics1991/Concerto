<?php

/**
*   EventNameGeneratorInterface
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

interface EventNameGeneratorInterface
{
    /**
    *   generateEventName
    *
    *   @param string $suffix
    *   @return string
    */
    public function generateEventName(
        string $suffix,
    ): string;
}
