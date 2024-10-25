<?php

/**
*   RateLimitterRepositoryInterface
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

use Concerto\auth\throttle\RateLimitterItemInterface;

interface RateLimitterRepositoryInterface
{
    /**
    *   save
    *
    *   @param RateLimitterItemInterface $item
    */
    public function save(
        RateLimitterItemInterface $item,
    );

    /**
    *   fetch
    *
    *   @param string $id
    *   @return array
    */
    public function fetch(
        string $id,
    ): array;

    /**
    *   delete
    *
    *   @param string $id
    */
    public function delete(
        string $id,
    );
}
