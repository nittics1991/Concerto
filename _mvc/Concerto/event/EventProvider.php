<?php

/**
*   EventProvider
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

use Psr\EventDispatcher\ListenerProviderInterface;
use Concerto\event\{
    NamedEventInterface,
    EventRegisterInterface,
};

class EventProvider implements
    ListenerProviderInterface,
    EventRegisterInterface
{
    /**
    *   @var array<string,array<int,array<int,callable>>>
    */
    private array $listeners = [];

    /**
    *   {inheritDoc}
    *
    *   @param int $priority
    *   @return static
    */
    public function addListener(
        string $id,
        callable $listener,
        int $priority = 0,
    ): static {
        $this->listeners[$id][$priority][] = $listener;

        return $this;
    }

    /**
    *   {inheritDoc}
    *
    *   @return callable[]
    */
    public function getListenersForEvent(
        object $event
    ): iterable {
        $eventName =
            $event instanceof NamedEventInterface ?
            $event->getEventName() :
            $event::class;

        yield from $this->getEvents(
            $eventName,
        );

        if (class_exists($eventName)) {
            yield from $this->getEvents(
                ...class_parents($eventName),
            );

            yield from $this->getEvents(
                ...class_implements($eventName),
            );
        }
    }

    /**
    *   getEvents
    *
    *   @param string|class-string ...$ids
    *   @return callable[]
    */
    private function getEvents(
        string ...$ids
    ): iterable {
        foreach ($ids as $id) {
            if (isset($this->listeners[$id])) {
                $priorityListeners =
                    $this->listeners[$id];

                ksort($priorityListeners, SORT_NUMERIC);

                foreach ($priorityListeners as $listeners) {
                    yield from $listeners;
                }
            }
        }
    }
}
