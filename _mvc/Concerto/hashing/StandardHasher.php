<?php

/**
*   StandardHasher
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\hashing;

use Concerto\hashing\HasherInterface;

class StandardHasher implements HasherInterface
{
    /**
    *   @var mixed[]
    */
    protected array $options;

    /**
    *   __costruct
    *
    *   @param mixed[] $options
    */
    public function __construct(
        array $options = []
    ) {
        $this->options = $options;
    }

    /**
    *   @inheritDoc
    *
    */
    public function hash(
        string $value
    ): string {
        return password_hash(
            $value,
            PASSWORD_DEFAULT,
            $this->options
        );
    }

    /**
    *   @inheritDoc
    *
    */
    public function verify(
        string $value,
        string $hashedValue
    ): bool {
        return password_verify($value, $hashedValue);
    }

    /**
    *   @inheritDoc
    *
    */
    public function check(
        string $hash
    ): bool {
        return ! password_needs_rehash(
            $hash,
            PASSWORD_DEFAULT,
            $this->options
        );
    }
}
