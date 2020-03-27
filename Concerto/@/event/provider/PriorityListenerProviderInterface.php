<?php
/**
*
*
*
**/
namespace Concerto\event\provider;

use Psr\EventDispatcher\ListenerProviderInterface;

interface PriorityListenerProviderInterface extends ListenerProviderInterface
{
    public function listen(string $eventType, callable $listener, int $priority = 1) : void;
}
