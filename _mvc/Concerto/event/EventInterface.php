<?php

/**
*   EventInterface
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

interface EventInterface
{
    /**
    *   getEventName
    *
    *   @return mixed
    */
    public function getEventData(): mixed;
}
