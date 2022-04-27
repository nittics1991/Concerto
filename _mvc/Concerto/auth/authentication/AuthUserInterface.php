<?php

/**
*   AuthUserInterface
*
*   @version 190513
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

interface AuthUserInterface
{
    /**
    *   ID取得
    *
    *   @return ?string
    */
    public function getId(): ?string;

    /**
    *   パスワード取得
    *
    *   @return ?string
    */
    public function getPassword(): ?string;
}
