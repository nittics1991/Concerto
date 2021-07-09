<?php

/**
*   StandardHasher
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\hashing;

use RuntimeException;
use Concerto\hashing\HasherInterface;

class StandardHasher implements HasherInterface
{
    /**
    *   options
    *
    *   @var mixed[]
    */
    protected $options;

    /**
    *   __costruct
    *
    *   @param mixed[] $options
    */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
    *   {inherit}
    *
    */
    public function hash(string $value): string
    {
        $result = password_hash($value, PASSWORD_DEFAULT, $this->options);
        if ($result === false) {
            throw new RuntimeException(
                "password hash error"
            );
        }
        return $result;
    }

    /**
    *   {inherit}
    *
    */
    public function verify(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }

    /**
    *   {inherit}
    *
    */
    public function check(string $hash): bool
    {
        return ! password_needs_rehash(
            $hash,
            PASSWORD_DEFAULT,
            $this->options
        );
    }
}
