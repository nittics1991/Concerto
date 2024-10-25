<?php

/**
*   RateLimitterRepositoryInterface
*
*   @version 240628
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

interface RateLimitterRepositoryInterface
{
    /**
    *   save
    *
    *   @param string $id
    */
    public function save(
        string $id,
    );

    /**
    *   fetch
    *
    *   @param string $id
    *   @param int $interval
    *   @return array
    */
    public function fetch(
        string $id,
        int $interval,
    ): array;

    /**
    *   delete
    *
    *   @param int $interval
    */
    public function delete(
        int $interval,
    );
}
