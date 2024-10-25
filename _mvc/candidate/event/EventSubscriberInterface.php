<?php

/**
*   EventSubscriberInterface
*
*   @version 170220
*/

declare(strict_types=1);

namespace candidate\event;

interface EventSubscriberInterface
{
    /**
    *   Returns an array of event names this subscriber wants to listen to.
    *
    *   The array keys are event names and the value can be:
    *
    *   * The method name to call (priority defaults to 0)
    *   * An array composed of the method name to call and the priority
    *   * An array of arrays composed of the method names to call
    *        and respectivepriorities, or 0 if unset
    *
    *   For instance:
    *
    *   * ['eventName' => 'methodName']
    *   * ['eventName' => ['methodName', $priority]]
    *   * ['eventName' => [['methodName1', $priority], ['methodName2']]]
    *
    *   @return array The event names to listen to
    */
    public static function getSubscribedEvents();
}
