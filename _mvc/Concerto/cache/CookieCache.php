<?php

/**
*   CookieCache
*
*   @version 220117
*   @memo    $_COOKIEはdot(.)はunderscore(_)に変換される
*                   有効期限セッションのデータはdelete/clearしても残る?(Edge)
*/

declare(strict_types=1);

namespace Concerto\cache;

use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\SimpleCacheTrait;

class CookieCache implements CacheInterface
{
    use SimpleCacheTrait;

    /**
    *   namespace
    *
    *   @var string
    */
    protected string $namespace;

    /**
    *   options
    *
    *   @var mixed[]
    */
    protected array $options = [
        'path' => '',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ];

    /**
    *   __construct
    *
    *   @param string $namespace
    *   @param mixed[] $options
    */
    public function __construct(
        string $namespace = 'CookieCache',
        array $options = []
    ) {
        $this->namespace = $namespace;
        $this->options = array_merge($this->options, $options);
    }

    /**
    *   {inherit}
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        $this->validateKey($key);
        return array_key_exists(
            "{$this->namespace}_{$key}",
            $_COOKIE
        ) ?
            json_decode($_COOKIE["{$this->namespace}_{$key}"]) :
            $default;
    }

    /**
    *   {inherit}
    */
    public function set(
        string $key,
        mixed $value,
        null|int|\DateInterval $ttl = null
    ): bool {
        $this->validateKey($key);
        $expires = $this->parseExpire($ttl);

        return setcookie(
            "{$this->namespace}_{$key}",
            is_null($value) ?
                '' : (string)json_encode($value),
            array_merge(
                $this->options,
                [
                    'expires' => empty($expires) ?
                        0 : time() + (int)$expires
                ]
            )
        );
    }

    /**
    *   {inherit}
    */
    public function delete(
        string $key
    ): bool {
        return $this->set($key, '', 1);
    }

    /**
    *   {inherit}
    */
    public function clear(): bool
    {
        $keys = array_keys($_COOKIE);

        foreach ($keys as $key) {
            if (mb_ereg_match("^{$this->namespace}_", $key)) {
                $this->delete(
                    (string)mb_ereg_replace(
                        "^{$this->namespace}_",
                        '',
                        $key
                    )
                );
            }
        }
        return true;
    }
}
