<?php

/**
*   RateLimitter
*
*   @version 240704
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use Countable;

interface RateLimitterManagerInterface extends Countable
{
    /**
    *   record
    */
    public function record();

    /**
    *   policy
    *
    *   @param int $interval
    *   @param int $limit
    *   @param string $name
    */
    public function policy(
        int $interval,
        int $limit,
        string $name,
    );

    /**
    *   isAccepted
    *
    *   @return bool
    */
    public function isAccepted(): bool;

    /**
    *   {inheritDoc}
    */
    public function count(): int;
}
