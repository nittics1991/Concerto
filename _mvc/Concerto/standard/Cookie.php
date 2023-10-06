<?php

/**
*   COOKIE
*
*   @version 230116
*/

declare(strict_types=1);

namespace Concerto\standard;

use InvalidArgumentException;

class Cookie
{
    /**
    *   @var mixed[]
    */
    protected array $params = [
        'expire' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(
        array $params = []
    ) {
        $this->params = array_replace(
            $this->params,
            $params
        );
    }

    /**
    *   @inheritDoc
    *
    */
    public function __get(
        string $name
    ): mixed {
        if (!isset($_COOKIE[$name])) {
            throw new InvalidArgumentException(
                "__get error:no property called {$name}"
            );
        }
        return $_COOKIE[$name];
    }

    /**
    *   @inheritDoc
    *
    */
    public function __set(
        string $name,
        mixed $value
    ): void {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                "__set error:data type error:{$name}"
            );
        }

        setcookie(
            $name,
            $value,
            intval($this->params['expire']),
            strval($this->params['path']),
            strval($this->params['domain']),
            (bool)$this->params['secure'],
            (bool)$this->params['httponly']
        );
    }

    /**
    *   @inheritDoc
    *
    */
    public function __isset(
        string $name
    ): bool {
        return isset($_COOKIE[$name]);
    }

    /**
    *   @inheritDoc
    *
    */
    public function __unset(
        string $name
    ): void {
        setcookie(
            $name,
            '',
            time() - 60 * 60,
            strval($this->params['path']),
            strval($this->params['domain']),
            (bool)$this->params['secure'],
            (bool)$this->params['httponly']
        );
    }

    /**
    *   delete
    *
    *   @return void
    */
    public function delete(): void
    {
        foreach (array_keys((array)$_COOKIE) as $name) {
            unset($this->$name);
        }
    }
}
