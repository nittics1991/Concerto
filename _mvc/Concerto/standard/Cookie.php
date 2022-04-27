<?php

/**
*   COOKIE
*
*   @version 201022
*/

declare(strict_types=1);

namespace Concerto\standard;

use InvalidArgumentException;

class Cookie
{
    /**
    *   Cookie設定
    *
    *   @var mixed[]
    */
    protected $params = [
        'expire' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $params 設定値
    */
    public function __construct(array $params = [])
    {
        $this->params = array_replace($this->params, $params);
    }

    /**
    *   {inherit}
    *
    */
    public function __get($key)
    {
        if (!isset($_COOKIE[$key])) {
            throw new InvalidArgumentException(
                "__get error:no property called {$key}"
            );
        }
        return $_COOKIE[$key];
    }

    /**
    *   {inherit}
    *
    */
    public function __set($key, $val)
    {
        if (!is_string($val)) {
            throw new InvalidArgumentException(
                "__set error:data type error:{$key}"
            );
        }

        setcookie(
            $key,
            $val,
            $this->params['expire'],
            $this->params['path'],
            $this->params['domain'],
            $this->params['secure'],
            $this->params['httponly']
        );
    }

    /**
    *   {inherit}
    *
    */
    public function __isset($key)
    {
        return isset($_COOKIE[$key]);
    }

    /**
    *   {inherit}
    *
    */
    public function __unset($key)
    {
        setcookie(
            $key,
            '',
            time() - 60 * 60,
            $this->params['path'],
            $this->params['domain'],
            $this->params['secure'],
            $this->params['httponly']
        );
    }

    /**
    *   全削除
    *
    */
    public function delete()
    {
        foreach (array_keys((array)$_COOKIE) as $key) {
            unset($this->$key);
        }
    }
}
