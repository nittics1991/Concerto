<?php

/**
*   StandardRandomNumberGenarator
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\hashing;

use InvalidArgumentException;

class StandardRandomNumberGenarator implements RandomNumberGenaratorInterface
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

        if ($this->length < 1) {
            throw new InvalidArgumentException(
                "must be length >= 1"
            );
        }
    }

    /**
    *   @inheritDoc
    *
    */
    public function generate(): string
    {
        return bin2hex(
            random_bytes(max(1, $this->length))
        );
    }
}
