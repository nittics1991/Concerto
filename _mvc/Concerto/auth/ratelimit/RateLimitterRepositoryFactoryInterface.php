<?php

/**
*   RateLimitterRepositoryFactoryInterface
*
*   @version 240628
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use Concerto\auth\ratelimit\RateLimitterRepositoryInterface;

interface RateLimitterRepositoryFactoryInterface
{
    /**
    *   build
    *
    *   @return RateLimitterRepositoryInterface
    */
    public function build(): RateLimitterRepositoryInterface;
}
