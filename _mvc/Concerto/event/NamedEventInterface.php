<?php

/**
*   NamedEventInterface
*
*   @versio 240827
*/

declare(strict_types=1);

namespace Concerto\event;

interface NamedEventInterface
{
    /**
    *   getEventName
    *
    *   @return string
    */
    public function getEventName(): string;
}
