<?php

/**
*   Caretaker(Cookie)
*
*   @version 160715
*/

declare(strict_types=1);

namespace Concerto\pattern;

use Concerto\standard\Cookie;

class MementoCookieCaretaker
{
    /**
    *   Cookie
    *
    *   @var object
    */
    protected $cookie;

    /**
    *   パラメータ
    *
    *   @var mixed[]
    */
    protected $params;

    /**
    *   __construct
    *
    *   @param Cookie $cookie
    *   @param mixed[] $params
    */
    public function __construct($cookie, array $params = [])
    {
        $this->cookie = $cookie;
        $this->params = $params;
    }

    /**
    *   入庫
    *
    *   @param mixed $key キー
    *   @param mixed $obj
    */
    public function setStorage($key, $obj)
    {
        $serialize = serialize($obj);
        $this->cookie->$key = $serialize;
    }

    /**
    *   出庫
    *
    *   @param mixed $key キー
    */
    public function getStorage($key)
    {
        if (isset($this->cookie->$key)) {
            return unserialize($this->cookie->$key);
        }
        return null;
    }
}
