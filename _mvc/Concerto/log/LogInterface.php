<?php

/**
*   LogInterface
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto\log;

interface LogInterface
{
    /**
    *   write
    *
    *   @param mixed $messages
    *   @return void
    */
    public function write(
        mixed $messages
    ): void;
}
