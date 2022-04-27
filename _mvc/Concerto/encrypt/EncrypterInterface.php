<?php

/**
*   StandardEncrypter
*
*   @version 200918
*/

declare(strict_types=1);

namespace Concerto\encrypt;

interface EncrypterInterface
{
    /**
    *   encrypt
    *
    *   @param string $value
    *   @return string
    */
    public function encrypt(string $value): string;

    /**
    *   decrypt
    *
    *   @param string $payload
    *   @return string
    */
    public function decrypt(string $payload): string;
}
