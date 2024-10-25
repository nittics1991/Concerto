<?php

/**
*   StringCompressorInterface
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\mbstring;

interface StringCompressorInterface
{
    /**
    *   compress
    *
    *   @param string $string
    *   @return string
    */
    public function compress(
        string $string
    ): string;

    /**
    *   expand
    *
    *   @param string $string
    *   @return string
    */
    public function expand(
        string $string
    ): string;

    /**
    *   isCompressed
    *
    *   @return bool
    */
    public function isCompressed(
        string $string
    ): bool;
}
