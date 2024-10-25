<?php

/**
*   ExcelEventDispatcher
*
*   @version 240910
*/

declare(strict_types=1);

namespace Concerto\excel;

use Psr\EventDispatcher\EventDispatcherInterface;

class ExcelEventDispatcher
{
    /**
    *   @var array<string,array<int,callable>>
    */
    protected static array $event_listeners = [];

    /**
    *   addListener
    *
    *   @param string $id
    *   @param callable $listener
    *   @return void
    */
    public function addListener(
        string $id,
        callable $listener,
    ): void {
        if (!isset(static::$event_listeners[$id])) {
            static::$event_listeners[$id] = [];
        }

        static::$event_listeners[$id][] = $listener;
    }

    /**
    *   {inheritDoc}
    *
    *   @param string $id
    */
    public function dispatch(
        object $event,
        string $id
    ): object {
        if (!isset(static::$event_listeners[$id])) {
            return $event;
        }

        foreach (static::$event_listeners[$id] as $listener) {
            call_user_func($listener, $event);
        }

        return $event;
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
        string $suffix = '',
    ): string {
        return $className .
            '::' .
            $methodName .
            '.' .
            $suffix;
    }
}
