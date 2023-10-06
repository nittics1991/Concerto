<?php

/**
*   EventProvider
*
*   @ver 230305
*   @see https://github.com/yiisoft/event-dispatcher
**/

declare(strict_types=1);

namespace Concerto\event;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionUnionType;
use Psr\EventDispatcherListenerProviderInterface;
use Concerto\event\EventRegisterInterface;

class EventProvider implements
    ListenerProviderInterface,
    EventRegisterInterface,
{
    /**
    *   @var callable[][]
    **/
    private array $listeners = [];

    /**
    *   add
    *
    *   @param callable $listener
    *   @param string ...$classNames
    *   @return self
    **/
    public function add(
        callable $listener,
        string ...$classNames,
    ) : self {
        if ($classNames === []) {
            $classNames =
                $this->createClassNames($listener);
        }

        foreach($classNames as $className) {
            $this->listeners[$className][] = $listener;
        }

        return self;
    }

    /**
    *   createClassNames
    *
    *   @param callable $listener
    *   @return string[]
    **/
    private function createClassNames(
        callable $listener,
    ) : array {
        $refFunction = new ReflectionFunction(
            Closure::fromCallable($listener)
        );

        $params = $refFunction->getParameters();

        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                "invalid listener argument",
            );
        }
            
        $paramType =  $params[0]->getType();

        if ($paramType instanceof ReflectionNamedType) {
            return [$paramType->getName()];
        }

        if (! $paramType instanceof ReflectionUnionType) {
            throw new InvalidArgumentException(
                "invalid listener argument type",
            );
        }

        return array_map(
            fn ($argType) => $argType->getName(),
            $paramType->getTypes(),
        );
    }

    /**
    *   {inheritDoc}
    **/
    public function getListenersForEvent(
        object $event
    ) : iterable {
        yield from $this->>getEvents(
            $event::class
        );

        yield from $this->>getEvents(
            ...array_values(class_parents($event))
        );
        
        yield from $this->getEvents(
            ...array_values(class_implements($event))
        );
    }

    /**
    *   getEvents
    *
    *   @param string ...$classNames
    *   @return iterable
    **/
    private function getEvents(
        string ...$classNames
    ) : iterable {
        foreach ($classNames as $className) {
            if (isset($this->listeners[$className])) {
                yield from $this->listeners[$className];
            }
        }
    }
}
