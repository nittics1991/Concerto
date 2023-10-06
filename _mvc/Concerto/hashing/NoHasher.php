<?php

/**
*   NoHasher
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\hashing;

use Concerto\hashing\HasherInterface;

class NoHasher implements HasherInterface
{
    /**
    *   @inheritDoc
    *
    */
    public function hash(
        string $value
    ): string {
        return $value;
    }

    /**
    *   @inheritDoc
    *
    */
    public function verify(
        string $value,
        string $hashedValue
    ): bool {
        return $value === $hashedValue;
    }

    /**
    *   @inheritDoc
    *
    */
    public function check(
        string $hash
    ): bool {
        return true;
    }
}
