<?php

/**
*   RateLimitterFactoryInterface
*
*   @version 240628
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use Concerto\auth\ratelimit\RateLimitterInterface;

interface RateLimitterFactoryInterface
{
    /**
    *   build
    *
    *   @param string $name
    *   @param int $interval
    *   @param int $limit
    *   @return RateLimitterInterface
    */
    public function build(
        string $name,
        int $interval,
        int $limit,
    ): RateLimitterInterface;
}
