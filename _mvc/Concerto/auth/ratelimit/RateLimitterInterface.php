<?php

/**
*   RateLimitterInterface
*
*   @version 240628
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

interface RateLimitterInterface
{
    /**
    *   isAccepted
    *
    *   @param string $id,
    *   @return bool
    */
    public function isAccepted(
        string $id,
    ): bool;
}
