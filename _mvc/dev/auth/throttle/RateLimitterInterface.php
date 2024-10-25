<?php

/**
*   RateLimitterInterface
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

interface RateLimitterInterface
{
    /**
    *   isAccepted
    *
    *   @param string $id
    *   @return bool
    */
    public function isAccepted(
        string $id,
    ): bool;
}
