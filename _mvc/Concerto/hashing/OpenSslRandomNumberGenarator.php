<?php

/**
*   OpenSslRandomNumberGenarator
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\hashing;

use RuntimeException;

class OpenSslRandomNumberGenarator implements RandomNumberGenaratorInterface
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
        $random = openssl_random_pseudo_bytes($this->length);
        if ($random === false) {
            throw new RuntimeException(
                "random no genetate error"
            );
        }
        return bin2hex($random);
    }
}
