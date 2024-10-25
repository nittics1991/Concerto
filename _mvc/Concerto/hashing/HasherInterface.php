<?php

/**
*   HasherInterface
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\hashing;

interface HasherInterface
{
    /**
    *   hash
    *
    *   @param string $value
    *   @return string
    */
    public function hash(
        string $value
    ): string;

    /**
    *   verify
    *
    *   @param string $value
    *   @param string $hashedValue
    *   @return bool
    */
    public function verify(
        string $value,
        string $hashedValue
    ): bool;

    /**
    *   check
    *
    *   @param string $hash
    *   @return bool
    */
    public function check(
        string $hash
    ): bool;
}
