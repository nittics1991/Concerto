<?php

/**
*   EventDispatcher
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

use Psr\EventDispatcher\{
    EventDispatcherInterface,
    ListenerProviderInterface,
    StoppableEventInterface,
};

class EventDispatcher implements EventDispatcherInterface
{
    /**
    *   @var ListenerProviderInterface
    */
    private ListenerProviderInterface $provider;

    /**
    *   __construct
    *
    *   @param ListenerProviderInterface $provider
    */
    public function __construct(
        ListenerProviderInterface $provider,
    ) {
        $this->provider = $provider;
    }

    /**
    *   {inheritDoc}
    */
    public function dispatch(
        object $event
    ): object {
        $listeners = $this->provider
            ->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            if (
                $event instanceof StoppableEventInterface &&
                $event->isPropagationStopped()
            ) {
                return $event;
            }

            $listener($event);
        }

        return $event;
    }

    /**
    *   getProvider
    *
    *   @return ListenerProviderInterface
    */
    public function getProvider(): ListenerProviderInterface
    {
        return $this->provider;
    }
}
