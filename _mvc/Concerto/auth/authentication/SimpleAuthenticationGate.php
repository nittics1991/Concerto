<?php

/**
*   SimpleAuthenticationGate
*
*   @version 190607
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthInterface;

class SimpleAuthenticationGate implements AuthInterface
{
    /**
    *   userId
    *
    *   @var string
    */
    private $userId;

    /**
    *   password
    *
    *   @var string
    */
    private $password;

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
    *   {inherit}
    *
    */
    public function login(string $user, string $password): bool
    {
        return $user === $this->userId && $password === $this->password;
    }
}
