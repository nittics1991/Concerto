<?php

/**
*   SimpleAuthenticationGate
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthInterface;

class SimpleAuthenticationGate implements AuthInterface
{
    /**
    *   @var string
    */
    private string $userId;

    /**
    *   @var string
    */
    private string $password;

    /**
    *   __construct
    *
    *   @param string $userId
    *   @param string $password
    */
    public function __construct(
        string $userId,
        string $password
    ) {
        $this->userId = $userId;

        $this->password = $password;
    }

    /**
    *   @inheritDoc
    */
    public function login(
        string $user,
        string $password
    ): bool {
        return $user === $this->userId &&
            $password === $this->password;
    }
}
