<?php

/**
*   RateLimitterRepositoryFactoryInterface
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

interface RateLimitterRepositoryFactoryInterface
{
    /**
    *   build
    *
    *   @return mixed
    */
    public function build(): mixed;
}
