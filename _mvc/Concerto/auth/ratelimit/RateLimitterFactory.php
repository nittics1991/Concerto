<?php

/**
*   RateLimitterFactory
*
*   @version 240704
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use Concerto\auth\ratelimit\{
    RateLimitterFactoryInterface,
    RateLimitterInterface,
    RateLimitterRepositoryInterface,
    SimpleRateLimitter,
};

class RateLimitterFactory implements RateLimitterFactoryInterface
{
    /**
    *   @var RateLimitterRepositoryInterface
    */
    protected RateLimitterRepositoryInterface $repository;

    /**
    *   __construct
    *
    *   @param RateLimitterRepositoryInterface $repository
    */
    public function __construct(
        RateLimitterRepositoryInterface $repository,
    ) {
        $this->repository = $repository;
    }

    /**
    *   {inheritDoc}
    */
    public function build(
        string $name,
        int $interval,
        int $limit,
    ): RateLimitterInterface {
        return match ($name) {
            'simple' => new SimpleRateLimitter(
                $this->repository,
                $interval,
                $limit,
            ),
            default => new SimpleRateLimitter(
                $this->repository,
                $interval,
                $limit,
            ),
        };
    }
}
