<?php

/**
*   OpenSslRandomNumberGenarator
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\hashing;

class OpenSslRandomNumberGenarator implements RandomNumberGenaratorInterface
{
    /**
    *   @var int
    */
    protected int $length = 16;

    /**
    *   __costruct
    *
    *   @param int $length
    */
    public function __construct(
        int $length = 16
    ) {
        $this->length = $length;
    }

    /**
    *   @inheritDoc
    *
    */
    public function generate(): string
    {
        return bin2hex(
            openssl_random_pseudo_bytes($this->length)
        );
    }
}
