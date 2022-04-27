<?php

/**
*   AuthInterface
*
*   @version 190513
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

interface AuthInterface
{
    /**
    *   認証情報確認
    *
    *   @param string $user
    *   @param string $password
    */
    public function login(string $user, string $password): bool;
}
