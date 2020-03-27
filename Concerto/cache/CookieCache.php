<?php

/**
 *   CookieCache
 *
 * @version 190521
 * @memo    $_COOKIEはdot(.)はunderscore(_)に変換される
 *                   有効期限セッションのデータはdelete/clearしても残る?(Edge)
 **/

declare(strict_types=1);

namespace Concerto\cache;

use Psr\SimpleCache\CacheInterface;
use Concerto\cache\SimpleCacheTrait;
 
class CookieCache implements CacheInterface
{
    use SimpleCacheTrait;
    
    /**
     *   namespace
     *
     * @var string
     **/
    protected $namespace;
    
    /**
     *   options
     *
     * @var array
     **/
    protected $options = [
        'path' => '',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
    ];
    
    /**
     *   __construct
     *
     * @param string $namespace
     * @param array  $options
     **/
    public function __construct(
        string $namespace = 'CookieCache',
        array $options = []
    ) {
        $this->namespace = $namespace;
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     *   {inherit}
     **/
    public function get($key, $default = null)
    {
        $this->validateKey($key);
        return array_key_exists("{$this->namespace}_{$key}", $_COOKIE) ?
            json_decode($_COOKIE["{$this->namespace}_{$key}"]) :
            $default;
    }
    
    /**
     *   {inherit}
     **/
    public function set($key, $value, $ttl = null)
    {
        $this->validateKey($key);
        $ttl = $this->parseExpire($ttl);
        
        return setcookie(
            "{$this->namespace}_{$key}",
            is_null($value) ? '' : (string)json_encode($value),
            empty($ttl) ? 0 : time() + (int)$ttl,
            $this->options['path'],
            $this->options['domain'],
            $this->options['secure'],
            $this->options['httponly']
        );
    }
    
    /**
     *   {inherit}
     **/
    public function delete($key)
    {
        return $this->set($key, '', 1);
    }
    
    /**
     *   {inherit}
     **/
    public function clear()
    {
        $keys = array_keys($_COOKIE);
        
        foreach ($keys as $key) {
            if (mb_ereg_match("^{$this->namespace}_", $key)) {
                $this->delete(
                    mb_ereg_replace("^{$this->namespace}_", '', $key)
                );
            }
        }
        return true;
    }
}
