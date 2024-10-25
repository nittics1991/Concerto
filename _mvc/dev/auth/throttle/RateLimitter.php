<?php

/**
*   RateLimitterInterface
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

use DateInterval;
use DateTimeImmutable;
use Concerto\auth\throttle\{
    RateLimitterInterface,
    RateLimitterItem,
    RateLimitterRepository,
};

class RateLimitter implements RateLimitterInterface
{
    /**
    *   @var RateLimitterRepository
    */
    private RateLimitterRepository $repository;

    /**
    *   @var DateInterval
    */
    private DateInterval $interval;

    /**
    *   @var int
    */
    private int $limit;

    /**
    *   __construct
    *
    *   @param RateLimitterRepository $repository
    *   @param DateInterval $interval
    *   @param int $limit
    */
    public function __construct(
        RateLimitterRepository $repository,
        DateInterval $interval,
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
        $expiration = (new DateTimeImmutable('now'))
            ->add($this->interval)
            ->format('U');

        $this->repository->save(
            new RateLimitterItem(
                $id,
                intval($expiration),
            ),
        );

        $this->repository->delete();

        $items = $this->repository->fetch($id);

        return count($items) === 0;
    }
}
