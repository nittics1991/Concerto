<?php

/**
*   EventDispatcher
*
*   @version 210903
*/

declare(strict_types=1);

namespace candidate\event;

use candidate\event\{
    Event,
    EventDispatcherInterface,
    EventInterface
};

/**
*   argument of listener implements EventInterface
*
*   example attache('eventName', function(EventInterface $event) {return;})
*/
class EventDispatcher implements EventDispatcherInterface
{
    /**
    *   listeners
    *
    *   @var array
    */
    private $listeners = [];

    /**
    *   sorted
    *
    *   @var array
    */
    private $sorted = [];

    /**
    *   results
    *
    *   @var array
    */
    private $results = [];

    /**
    *   {inherit}
    *
    */
    public function attach($eventName, $listener, $priority = 0)
    {
        if (!is_string($eventName)) {
            return false;
        }

        if (!is_callable($listener)) {
            return false;
        }

        if (!is_int($priority)) {
            return false;
        }

        $this->listeners[$eventName][$priority][] = $listener;
        unset($this->sorted[$eventName]);
        return true;
    }

    /**
    *   {inherit}
    *
    */
    public function detach($eventName, $listener)
    {
        if (!is_string($eventName)) {
            return false;
        }

        if (!isset($this->listeners[$eventName]) || !is_callable($listener)) {
            return false;
        }

        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            if (false !== ($key = array_search($listener, $listeners, true))) {
                unset(
                    $this->listeners[$eventName][$priority][$key],
                    $this->sorted[$eventName],
                    $this->results[$eventName]
                );
            }
        }
        return true;
    }

    /**
    *   {inherit}
    *
    */
    public function clearListeners($eventName = null)
    {
        if (is_null($eventName)) {
            $this->listeners = [];
            $this->sorted = [];
            $this->results = [];
        }
        $this->listeners[$eventName] = [];
        $this->sorted[$eventName] = [];
        $this->results[$eventName] = [];
    }

    /**
    *   {inherit}
    *
    */
    public function trigger($event, $target = null, $argv = [])
    {
        if (is_string($event)) {
            $event = new Event($event, $target, $argv);
        }

        //not use $target, $argv
        if ($event instanceof EventInterface) {
            $eventName = $event->getName();
        }

        if ($listeners = $this->getListeners($eventName)) {
            return $this->doDispatch($listeners, $event);
        }
        return null;
    }

    /**
    *   doDispatch
    *
    *   @param array $listeners callable[]
    *   @param EventInterface $event
    *   @return mixed
    */
    protected function doDispatch($listeners, EventInterface $event)
    {
        $this->results[$event->getName()] = [];
        $result = null;

        foreach ($listeners as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }
            $result = $this->results[$event->getName()][] =
                call_user_func($listener, $event);
        }
        return $result;
    }

    /**
    *   {inherit}
    *
    */
    public function getListeners($eventName = null)
    {
        if (null !== $eventName) {
            if (!isset($this->listeners[$eventName])) {
                return [];
            }

            if (!isset($this->sorted[$eventName])) {
                $this->sortListeners($eventName);
            }
            return $this->sorted[$eventName];
        }

        foreach (array_keys($this->listeners) as $eventName) {
            if (!isset($this->sorted[$eventName])) {
                $this->sortListeners($eventName);
            }
        }
        return array_filter($this->sorted);
    }

    /**
    *   sortListeners
    *
    *   @param string $eventName
    */
    private function sortListeners($eventName)
    {
        krsort($this->listeners[$eventName]);
        $this->sorted[$eventName] =
            call_user_func_array('array_merge', $this->listeners[$eventName]);
    }

    /**
    *   {inherit}
    *
    */
    public function getListenerPriority($eventName, $listener)
    {
        if (!isset($this->listeners[$eventName])) {
            return null;
        }

        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            if (false !== in_array($listener, $listeners, true)) {
                return $priority;
            }
        }
        return null;
    }

    /**
    *   {inherit}
    *
    */
    public function getResults($eventName = null)
    {
        if (is_null($eventName)) {
            return $this->results;
        }

        if (!isset($this->results[$eventName])) {
            return [];
        }
        return $this->results[$eventName];
    }

    /**
    *   {inherit}
    *
    */
    public function hasListeners($eventName = null)
    {
        return (bool) count($this->getListeners($eventName));
    }

    /**
    *   {inherit}
    *
    */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->attach($eventName, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $this->attach(
                    $eventName,
                    [$subscriber, $params[0]],
                    isset($params[1]) ? $params[1] : 0
                );
            } else {
                foreach ($params as $listener) {
                    $this->attach(
                        $eventName,
                        [$subscriber, $listener[0]],
                        isset($listener[1]) ? $listener[1] : 0
                    );
                }
            }
        }
    }

    /**
    *   {inherit}
    *
    */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->detach($eventName, [$subscriber, $listener[0]]);
                }
            } else {
                $this->detach(
                    $eventName,
                    [$subscriber, is_string($params) ? $params : $params[0]]
                );
            }
        }
    }
}
