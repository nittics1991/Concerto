<?php

/**
*   EventObject
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

use Concerto\event\{
    NamedEventInterface,
    NamedEventTrait,
    EventInterface,
};

class EventObject implements
    EventInterface,
    NamedEventInterface
{
    use NamedEventTrait;

    /**
    *   @var mixed
    */
    private mixed $eventData;

    /**
    *   __construct
    *
    *   @param string $eventName
    *   @param mixed $eventData
    */
    public function __construct(
        string $eventName,
        mixed $eventData = null,
    ) {
        $this->setEventName($eventName);
        $this->eventData = $eventData;
    }

    /**
    *   create
    *
    *   @param string $eventName
    *   @param mixed $eventData
    *   @return self
    */
    public static function create(
        string $eventName,
        mixed $eventData = null,
    ): self {
        return new self(
            $eventName,
            $eventData,
        );
    }

    /**
    *   {inheritDoc}
    */
    public function getEventData(): mixed
    {
        return $this->eventData;
    }
}
