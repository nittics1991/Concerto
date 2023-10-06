<?php

/**
*   EventProvider
*
*   @ver 230305
*   @see https://github.com/yiisoft/event-dispatcher
**/

declare(strict_types=1);

namespace Concerto\event;

interface EventRegisterInterface
{
    /**
    *   add
    *
    *   @param callable $listener
    *   @param string ...$classNames
    *   @return mixed
    **/
    public function add(
        callable $listener,
        string ...$classNames,
    ):mixed;
}
