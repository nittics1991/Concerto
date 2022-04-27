<?php

/**
*   CSRF
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\auth;

use InvalidArgumentException;
use RuntimeException;
use Concerto\standard\Session;

class Csrf
{
    /**
    *   SESSION名前空間
    *
    *   @var string
    */
    protected static $namespace = 'csrf';

    /**
    *   token最大byte
    *
    *   @var int
    */
    protected static $token_max_byte = 32;

    /**
    *   token保持数
    *
    *   @var int
    */
    protected static $token_length = 50;

    /**
    *   token生成
    *
    *   @param ?int $timeout
    *   @param int $length
    *   @return string
    */
    public static function generate(
        ?int $timeout = 30,
        int $length = 16
    ): string {
        if ($length > self::$token_max_byte) {
            throw new InvalidArgumentException("invalid parameter");
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $token = bin2hex(random_bytes($length));

        if (!$token) {
            throw new RuntimeException('require openssl');
        }

        $_SESSION[self::$namespace][$token] = strtotime("+{$timeout} min");
        if (count($_SESSION[self::$namespace]) > self::$token_length) {
            array_shift($_SESSION[self::$namespace]);
        }

        self::refresh();
        return $token;
    }

    /**
    *   バリデート
    *
    *   @param ?string $token
    *   @param ?bool $remove token削除(true:削除)
    *   @return bool
    */
    public static function isValid(?string $token, ?bool $remove = true)
    {
        if (!mb_check_encoding((string)$token)) {
            return false;
        }

        if (session_status() != PHP_SESSION_ACTIVE) {
            @session_start();
        }

        self::refresh();

        if (empty($token) || !isset($_SESSION[self::$namespace])) {
            return false;
        }
        $result = array_key_exists($token, (array)$_SESSION[self::$namespace]);

        if ($remove) {
            self::remove($token);
        }
        return $result;
    }

    /**
    *   token削除
    *
    *   @param ?string $token
    *   @return void
    */
    public static function remove(?string $token = null)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            @session_start();
        }

        self::refresh();

        if (is_null($token)) {
            unset($_SESSION[self::$namespace]);
        } elseif (array_key_exists($token, $_SESSION[self::$namespace])) {
            unset($_SESSION[self::$namespace][$token]);
        }
    }

    /**
    *   タイムアウトtoken削除
    *
    *   @return void
    */
    public static function refresh()
    {
        if (session_status()  != PHP_SESSION_ACTIVE) {
            @session_start();
        }
        $now = time();

        if (!isset($_SESSION[self::$namespace])) {
            return;
        }

        foreach ((array)$_SESSION[self::$namespace] as $key => $val) {
            if ($val < $now) {
                unset($_SESSION[self::$namespace][$key]);
            }
        }
    }
}
