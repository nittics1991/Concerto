<?php
/**
*
*
*
**/
namespace Concerto\event\provider;

use Concerto\event\provider\StackListenerProviderInterface;

class StackListenerProvider implements AttachableListenerProviderInterface
{
    private $listeners = [];

    public function getListenersForEvent(object $event) : iterable
    {
        foreach ($this->listeners as $eventType => $listeners) {
            if (! $event instanceof $eventType) {
                continue;
            }
            foreach ($listeners as $listener) {
                yield $listener;
            }
        }
    }

    public function listen(string $eventType, callable $listener) : void
    {
        if (isset($this->listeners[$eventType])
            && in_array($listener, $this->listeners[$eventType], true)
        ) {
            // Duplicate detected
            return;
        }
        $this->listeners[$eventType][] = $listener;
    }
}
