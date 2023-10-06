<?php

/**
*   Caretaker(Cookie)
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\pattern;

use Concerto\standard\Cookie;

class MementoCookieCaretaker
{
    /**
    *   @var Cookie
    */
    protected Cookie $cookie;

    /**
    *   __construct
    *
    *   @param Cookie $cookie
    */
    public function __construct(
        Cookie $cookie,
    ) {
        $this->cookie = $cookie;
    }

    /**
    *   入庫
    *
    *   @param string $key
    *   @param mixed $value
    */
    public function setStorage(
        string $key,
        mixed $value
    ): void {
        $serialized = serialize($value);

        $this->cookie->$key = $serialized;
    }

    /**
    *   出庫
    *
    *   @param string $key
    *   preturn mixed
    */
    public function getStorage(
        string $key
    ): mixed {
        if (isset($this->cookie->$key)) {
            return unserialize($this->cookie->$key);
        }

        return null;
    }
}
