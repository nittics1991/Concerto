<?php

/**
*   RandomNumberGenaratorInterface
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\hashing;

interface RandomNumberGenaratorInterface
{
    /**
    *   generate
    *
    *   @return string
    */
    public function generate(): string;
}
