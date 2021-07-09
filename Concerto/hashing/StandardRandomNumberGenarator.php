<?php

/**
*   StandardRandomNumberGenarator
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\hashing;

class StandardRandomNumberGenarator implements RandomNumberGenaratorInterface
{
    /**
    *   length
    *
    *   @var int
    */
    protected $length = 16;

    /**
    *   __costruct
    *
    *   @param int $length
    */
    public function __construct(int $length = 16)
    {
        $this->length = $length;
    }

    /**
    *   {inherit}
    *
    */
    public function generate(): string
    {
        return bin2hex(random_bytes($this->length));
    }
}
