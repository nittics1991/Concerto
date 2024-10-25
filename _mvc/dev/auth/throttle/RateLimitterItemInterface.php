<?php

/**
*   RateLimitterItemInterface
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

interface RateLimitterItemInterface
{
    /**
    *   getId
    *
    *   @return string
    */
    public function getId(): string;

    /**
    *   getExpiration
    *
    *   @return string
    */
    public function getExpiration(): int;
}
