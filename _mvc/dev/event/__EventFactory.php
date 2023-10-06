<?php

/**
*   EventDispatcher
*
*   @ver 230305
**/

declare(strict_types=1);

namespace Concerto\event;

use Psr\EventDispatcher\{
    EventDispatcherInterface,
    ListenerProviderInterface,
    StoppableEventInterface,
};



use Concerto\event\{
    EventDispatcher,
    EventProvider,
    EventRegisterInterface,
};

class StandardEvent
{
    /**
    *   @var EventDispatcherInterface
    **/
    private EventDispatcherInterface $dispatcher;

    /**
    *   @var ListenerProviderInterface&EventRegisterInterface&nul
    **/
    private ListenerProviderInterface&EventRegisterInterface $provider;

    /**
    *   __construct
    *
    *   @param ListenerProviderInterface&EventRegisterInterface $provider
    *   @param EventDispatcherInterface $dispatcher
    **/
    public function __construct(
        ?ListenerProviderInterface&EventRegisterInterface $provider = null,
        ?EventDispatcherInterface $dispatcher = null,
    ) {
        $this->provider = $provider?? new EventProvider();

        $this->dispatcher = $dispatcher??
            new EventProvider($this->provider);
    }



    public static function add(
        callable $listener,
        string ...$classNames,
    ) : self {


        





    /**
    *   {inheritDoc}
    **/
    public function dispatch(
        object $event
    ) : object {
        $listeners = $this->provider
            ->getListenersForEvent($event)
        
        foreach ($listeners as $listener) {
            if (
                $event instanceof StoppableEventInterface &&
                $event->isPropagationStopped()
            ) {
                return $event;
            }

            $spoofableEvent = $event;
            $listener($spoofableEvent);
        }

        return $event;
    }
}
