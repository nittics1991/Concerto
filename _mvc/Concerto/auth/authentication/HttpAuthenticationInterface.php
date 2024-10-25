<?php

/**
*   HttpAuthenticationInterface
*
*   @version 240708
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

interface HttpAuthenticationInterface
{
    /**
    *   getAuthType
    *
    *   @return ?string
    */
    public function getAuthType(): ?string;

    /**
    *   getAuthUser
    *
    *   @return ?string
    */
    public function getAuthUser(): ?string;

    /**
    *   getAuthPassword
    *
    *   @return ?string
    */
    public function getAuthPassword(): ?string;

    /**
    *   getAuthDigest
    *
    *   @return ?string
    */
    public function getAuthDigest(): ?string;
}
