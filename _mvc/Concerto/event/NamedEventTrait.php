<?php

/**
*   NamedEventTrait
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

trait NamedEventTrait
{
    /**
    *   @var string
    */
    protected string $eventName;

    /**
    *   setEventName
    *
    *   @param string $eventName
    *   @return void
    */
    public function setEventName(
        string $eventName,
    ): void {
        $this->eventName = $eventName;
    }

    /**
    *   {inheritDoc}
    */
    public function getEventName(): string
    {
        return $this->eventName;
    }
}
