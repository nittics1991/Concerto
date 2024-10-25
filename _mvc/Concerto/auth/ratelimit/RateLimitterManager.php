<?php

/**
*   RateLimitter
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use RuntimeException;
use Concerto\auth\ratelimit\{
    RateLimitterFactoryInterface,
    RateLimitterInterface,
    RateLimitterManagerInterface,
    RateLimitterRepositoryInterface,
};
use Concerto\standard\Server;

class RateLimitterManager implements RateLimitterManagerInterface
{
    /**
    *   @var RateLimitterRepositoryInterface
    */
    protected RateLimitterRepositoryInterface $repository;

    /**
    *   @var RateLimitterFactoryInterface
    */
    protected RateLimitterFactoryInterface $limitterFactory;

    /**
    *   @var int
    */
    protected int $expiration;

    /**
    *   @var int<0,100>
    */
    protected int $garbagePer;

    /**
    *   @var string
    */
    protected string $id;

    /**
    *   @var RateLimitterInterface
    */
    protected RateLimitterInterface $limitter;

    /**
    *   __construct
    *
    *   @param RateLimitterRepositoryInterface $repository
    *   @param RateLimitterFactoryInterface $limitterFactory
    *   @param ?int $expiration sec
    *   @param ?int $garbagePer
    */
    public function __construct(
        RateLimitterRepositoryInterface $repository,
        RateLimitterFactoryInterface $limitterFactory,
        ?int $expiration = null,
        ?int $garbagePer = null,
    ) {
        $this->repository = $repository;
        $this->limitterFactory = $limitterFactory;
        $this->expiration = $expiration ?? 60 * 60 * 2;

        $garbagePer = $garbagePer === null ?
            5 : abs($garbagePer);

        $this->garbagePer = $garbagePer <= 100 ?
            $garbagePer : 100;

        $this->id = $this->getId();
    }

    /**
    *   getId
    *
    *   @return string
    */
    protected function getId(): string
    {
        $ip = Server::has('x-forwarded-for') ?
            Server::get('x-forwarded-for') :
            Server::get('remote_addr');

        $id = mb_ereg_replace('\.', '_', strval($ip));

        if (empty($id)) {
            throw new RuntimeException(
                "could not get IP address"
            );
        }

        return $id;
    }

    /**
    *   {inheritDoc}
    *
    *   @return static
    */
    public function record(): static
    {
        $this->garbage();

        $this->repository->save($this->id);

        return $this;
    }

    /**
    *   garbage
    *
    *   @return void
    */
    public function garbage(): void
    {
        $randumNo = $this->generateRandumNo();

        $hit = $randumNo <= $this->garbagePer;

        if ($hit) {
            $this->repository->delete($this->expiration);
        }
    }

    /**
    *   generateRandumNo
    *
    *   @return int<1, 100>
    */
    protected function generateRandumNo(): int
    {
        return random_int(1, 100);
    }

    /**
    *   {inheritDoc}
    *
    *   @param ?string $name
    *   @return static
    */
    public function policy(
        int $interval,
        int $limit,
        ?string $name = null,
    ): static {
        $this->limitter = $this->limitterFactory->build(
            $name ?? 'simple',
            $interval,
            $limit,
        );

        return clone $this;
    }

    /**
    *   {inheritDoc}
    */
    public function isAccepted(): bool
    {
        return $this->limitter->isAccepted(
            $this->id,
        );
    }

    /**
    *   {inheritDoc}
    */
    public function count(): int
    {
        $items = $this->repository->fetch(
            $this->id,
            $this->expiration,
        );

        return count($items);
    }
}
