<?php

/**
*   AuthHistoryRepositoryInterface
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserInterface;

interface AuthHistoryRepositoryInterface
{
    /**
    *   記録
    *
    *   @param AuthUserInterface $authUser
    *   @param mixed[] $contents
    */
    public function record(
        AuthUserInterface $authUser,
        array $contents = []
    );
}
