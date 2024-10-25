<?php

/**
*   EventRegisterInterface
*
*   @version 240913
*/

declare(strict_types=1);

namespace Concerto\event;

interface EventRegisterInterface
{
    /**
    *   addListener
    *
    *   @param string $id
    *   @param callable $listener
    *   @return mixed
    */
    public function addListener(
        string $id,
        callable $listener,
    ): mixed;
}
