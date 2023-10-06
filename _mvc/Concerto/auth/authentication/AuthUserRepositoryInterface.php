<?php

/**
*   AuthUserRepositoryInterface
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

interface AuthUserRepositoryInterface
{
    /**
    *   ユーザID検索
    *
    *   @param string $user
    *   @return ?AuthUserInterface
    */
    public function findByUserId(
        string $user
    ): ?AuthUserInterface;

    /**
    *   ユーザの存在
    *
    *   @param string $user
    *   @return bool
    */
    public function exists(
        string $user
    ): bool;

    /**
    *   パスワード確認
    *
    *   @param AuthUserInterface $user
    *   @param string $password
    *   @return bool
    */
    public function validatePassword(
        AuthUserInterface $user,
        string $password
    ): bool;
}
