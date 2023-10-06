<?php

/**
*   EventDispatcher
*
*   @ver 230305
**/

declare(strict_types=1);

namespace Concerto\event;

use Concerto\event\{
    EventDispatcher,
    EventProvider,
};

final class StandardEvent
{
    /**
    *   @var EventProvider
    **/
    private static EventProvider $provider;

    /**
    *   @var EventDispatcher
    **/
    private static EventDispatcher $dispatcher;

    public static function add(
        callable $listener,
        string ...$classNames,
    ) : self {
        if (!$this->provider) {
            $this->provider = $new EventProvider();
        }

        $result = call_user_func_array(
            [$this->provider, 'add'],
            array_merge([$listener], $classNames),
        );

        if ($result === false) {
            throw new RuntimeException(
                "faild call add method",
            )
        }

        return self;
    }

    public static function dispatch(
        object $event
    ) : object {
        if (!$this->provider) {
            $this->provider = $new EventProvider();
        }

        if (!$this->dispatcher) {
            $this->dispatcher = $new EventDispatcher(
                $this->provider,
            );
        }

        $result = call_user_func_array(
            [$this->dispatcher, 'dispatch'],
            array_merge([$listener], $classNames),
        );

        if ($result === false) {
            throw new RuntimeException(
                "faild call dispatch method",
            )
        }
        
        return $result;
    }
}
