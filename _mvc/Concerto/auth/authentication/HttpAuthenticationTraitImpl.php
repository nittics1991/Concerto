<?php

/**
*   HttpAuthenticationTraitImpl
*
*   @version 240708
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

trait HttpAuthenticationTraitImpl
{
    /**
    *   {inheritDoc}
    */
    public function getAuthType(): ?string
    {
        return $_SERVER['AUTH_TYPE'] ?? null;
    }

    /**
    *   {inheritDoc}
    */
    public function getAuthUser(): ?string
    {
        return $_SERVER['PHP_AUTH_USER'] ?? null;
    }

    /**
    *   {inheritDoc}
    */
    public function getAuthPassword(): ?string
    {
        return $_SERVER['PHP_AUTH_PW'] ?? null;
    }

    /**
    *   {inheritDoc}
    */
    public function getAuthDigest(): ?string
    {
        return $_SERVER['PHP_AUTH_DIGEST'] ?? null;
    }
}
