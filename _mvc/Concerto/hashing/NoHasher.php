<?php

/**
*   NoHasher
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\hashing;

use Concerto\hashing\HasherInterface;

class NoHasher implements HasherInterface
{
    /**
    *   {inherit}
    *
    */
    public function hash(string $value): string
    {
        return $value;
    }

    /**
    *   {inherit}
    *
    */
    public function verify(string $value, string $hashedValue): bool
    {
        return $value === $hashedValue;
    }

    /**
    *   {inherit}
    *
    */
    public function check(string $hash): bool
    {
        return true;
    }
}
