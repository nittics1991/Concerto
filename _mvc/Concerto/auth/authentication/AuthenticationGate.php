<?php

/**
*   AuthenticationGate
*
*   @version 190522
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthInterface;
use Concerto\auth\authentication\AuthUserInterface;
use Concerto\auth\authentication\AuthUserRepositoryInterface;

class AuthenticationGate implements AuthInterface
{
    /**
    *   authUserRepository
    *
    *   @var AuthUserRepositoryInterface
    */
    protected $authUserRepository;

    /**
    *   __construct
    *
    *   @param AuthUserRepositoryInterface $authUserRepository
    */
    public function __construct(
        AuthUserRepositoryInterface $authUserRepository
    ) {
        $this->authUserRepository = $authUserRepository;
    }

    /**
    *   {inherit}
    *
    */
    public function login(string $user, string $password): bool
    {
        $authUser = $this->authUserRepository->findByUserId($user);
        if (is_null($authUser)) {
            return false;
        }
        return $this->authUserRepository->validatePassword(
            $authUser,
            $password
        );
    }
}
