<?php

/**
*   EventManager
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

use RuntimeException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Concerto\event\{
    EventDispatcher,
    EventNameBuilder,
    EventNameGeneratorInterface,
    EventObject,
    EventProvider,
};

class EventManager implements
    EventDispatcherInterface,
    EventNameGeneratorInterface
{
    /**
    *   @var EventDispatcher
    */
    private static EventDispatcher $dispatcher;

    /**
    *   @var EventProvider
    */
    private static EventProvider $provider;

    /**
    *   __construct
    *
    *   @param EventDispatcher $dispatcher
    */
    public function __construct(
        EventDispatcher $dispatcher,
    ) {
        if (!isset(self::$dispatcher)) {
            self::$dispatcher = $dispatcher;

            $provider = self::$dispatcher->getProvider();

            if (!$provider instanceof EventProvider) {
                throw new RuntimeException(
                    "provider must be EventProvider",
                );
            }

            self::$provider = $provider;
        }
    }

    /**
    *   create
    *
    *   @param ?EventDispatcher $dispatcher
    *   @return self
    */
    public static function create(
        ?EventDispatcher $dispatcher = null,
    ): self {
        if (isset($dispatcher)) {
            return new self($dispatcher);
        }

        return new self(
            new EventDispatcher(
                new EventProvider(),
            ),
        );
    }

    /**
    *   {inheritDoc}
    */
    public function dispatch(
        object $event
    ): object {
        return self::$dispatcher->dispatch($event);
    }

    /**
    *   addListener
    *
    *   @param string $id
    *   @param int $priority
    *   @return static
    */
    public function addListener(
        string $id,
        callable $listener,
        int $priority = 0,
    ): static {
        self::$provider->addListener(
            $id,
            $listener,
            $priority,
        );

        return $this;
    }

    /**
    *   createEvent
    *
    *   @param string $eventNameSuffix
    *   @param mixed $data
    *   @return EventObject
    */
    public function createEvent(
        string $eventNameSuffix,
        mixed $data,
    ): EventObject {
        return new EventObject(
            $this->generateEventName($eventNameSuffix),
            $data,
        );
    }

    /**
    *   {inheritDoc}
    */
    public function generateEventName(
        string $suffix = '',
    ): string {
        $stackTrace = debug_backtrace();

        if (
            !isset($stackTrace[2]) ||
            !isset($stackTrace[2]['class'])
        ) {
            throw new RuntimeException(
                "must be called metthod"
            );
        }

        return $stackTrace[2]['class'] .
            '::' .
            $stackTrace[2]['function'] .
            '.' .
            $suffix;
    }

    /**
    *   buildEventName
    *
    *   @param string $className
    *   @param string $methodName
    *   @param string $suffix
    *   @return string
    */
    public function buildEventName(
        string $className,
        string $methodName,
        string $suffix,
    ): string {
        return EventNameBuilder::build(
            $className,
            $methodName,
            $suffix,
        );
    }
}
