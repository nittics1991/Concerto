<?php

/**
*   RateLimitterItem
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

class RateLimitterItem implements RateLimitterItemInterface
{
    /**
    *   @var string
    */
    private string $id;

    /**
    *   @var int
    */
    private int $expiration;

    /**
    *   __construct
    *
    *   @param string $id
    *   @param int $expiration
    */
    public function __construct(
        string $id,
        int $expiration,
    ) {
        $this->id = $id;
        $this->expiration = $expiration;
    }

    /**
    *   {inheritDoc}
    */
    public function getId(): string
    {
        return $this->id;
    }

    /**
    *   {inheritDoc}
    */
    public function getExpiration(): int
    {
        return $this->expiration;
    }
}
