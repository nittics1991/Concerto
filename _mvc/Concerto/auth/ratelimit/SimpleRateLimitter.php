<?php

/**
*   SimpleRateLimitterPolicy
*
*   @version 240704
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use DateInterval;
use Concerto\auth\ratelimit\{
    RateLimitterInterface,
    RateLimitterRepositoryInterface,
};

class SimpleRateLimitter implements RateLimitterInterface
{
    /**
    *   @var RateLimitterRepositoryInterface
    */
    protected RateLimitterRepositoryInterface $repository;

    /**
    *   @var int
    */
    protected int $interval;

    /**
    *   @var int
    */
    protected int $limit;

    /**
    *   __construct
    *
    *   @param RateLimitterRepositoryInterface $repository
    *   @param int $interval
    *   @param int $limit
    */
    public function __construct(
        RateLimitterRepositoryInterface $repository,
        int $interval,
        int $limit,
    ) {
        $this->repository = $repository;
        $this->interval = $interval;
        $this->limit = $limit;
    }

    /**
    *   {inheritDoc}
    */
    public function isAccepted(
        string $id,
    ): bool {
        $items = $this->repository->fetch(
            $id,
            $this->interval,
        );

        return count($items) <= $this->limit;
    }
}
