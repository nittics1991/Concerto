<?php

/**
*   AuthenticationGate
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\{
    AuthInterface,
    AuthUserInterface,
    AuthUserRepositoryInterface
};

class AuthenticationGate implements AuthInterface
{
    /**
    *   @var AuthUserRepositoryInterface
    */
    protected AuthUserRepositoryInterface $authUserRepository;

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
    *   @inheritDoc
    */
    public function login(
        string $user,
        string $password
    ): bool {
        $authUser = $this->authUserRepository
            ->findByUserId($user);

        if (is_null($authUser)) {
            return false;
        }

        return $this->authUserRepository->validatePassword(
            $authUser,
            $password
        );
    }
}
