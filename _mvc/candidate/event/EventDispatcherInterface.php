<?php

/**
*   EventDispatcherInterface
*
*   @version 170220
*/

declare(strict_types=1);

namespace candidate\event;

interface EventDispatcherInterface extends EventManagerInterface
{
    /**
    *   Adds an event subscriber.
    *
    *   The subscriber is asked for all the events he is
    *   interested in and added as a listener for these events.
    *
    *   @param EventSubscriberInterface $subscriber The subscriber
    */
    public function addSubscriber(EventSubscriberInterface $subscriber);

    /**
    *   Removes an event subscriber.
    *
    *   @param EventSubscriberInterface $subscriber The subscriber
    */
    public function removeSubscriber(EventSubscriberInterface $subscriber);

    /**
    *   Gets the listeners of a specific event or
    *       all listeners sorted by descending priority.
    *
    *   @param string $eventName The name of the event
    *   @return array The event listeners for the specified event,
    *       or all event listeners by event name
    */
    public function getListeners($eventName = null);

    /**
    *   Gets the listener priority for a specific event.
    *
    *   Returns null if the event or the listener does not exist.
    *
    *   @param string   $eventName The name of the event
    *   @param callable $listener  The listener
    *   @return int|null The event listener priority
    */
    public function getListenerPriority($eventName, $listener);

    /**
    *   Checks whether an event has any registered listeners.
    *
    *   @param string $eventName The name of the event
    *   @return bool true if the specified event has
    *       any listeners, false otherwise
    */
    public function hasListeners($eventName = null);

    /**
    *   getResult
    *
    *   @param string $eventName
    *   @return array
    */
    public function getResults($eventName = null);
}
