<?php

/**
 *   SessionCache
 *
 * @version 190605
 **/

declare(strict_types=1);

namespace Concerto\cache;

use Psr\SimpleCache\CacheInterface;
use Concerto\cache\CacheException;
use Concerto\cache\SimpleCacheTrait;
 
class SessionCache implements CacheInterface
{
    use SimpleCacheTrait;
    
    /**
     *   namespace
     *
     * @var string
     **/
    protected $namespace;
    
    /**
     *   __construct
     *
     * @param string $namespace
     **/
    public function __construct(string $namespace = 'SessionCache')
    {
        $this->namespace = $namespace;
    }
    
    /**
     *   __destruct
     **/
    public function __destruct()
    {
        $this->writeSession();
    }
    
    /**
     *   writeSession
     **/
    protected function writeSession()
    {
        session_write_close();
    }
    
    /**
     *   startSession
     **/
    protected function startSession()
    {
        if (session_status() != PHP_SESSION_ACTIVE && ! headers_sent()) {
            session_start();
        }
    }
    
    /**
     *   {inherit}
     **/
    public function get($key, $default = null)
    {
        $this->validateKey($key);
        $this->startSession();
        $result = $_SESSION[$this->namespace][$key] ?? $default;
        $this->writeSession();
        return $result;
    }
    
    /**
     *   {inherit}
     **/
    public function set($key, $value, $ttl = null)
    {
        $this->validateKey($key);
        $this->startSession();
        $_SESSION[$this->namespace][$key] = $value;
        $this->writeSession();
        return true;
    }
    
    /**
     *   {inherit}
     **/
    public function delete($key)
    {
        $this->validateKey($key);
        $this->startSession();
        unset($_SESSION[$this->namespace][$key]);
        $this->writeSession();
        return true;
    }
    
    /**
     *   {inherit}
     **/
    public function clear()
    {
        $this->startSession();
        unset($_SESSION[$this->namespace]);
        $this->writeSession();
        return true;
    }
    
    /**
     *   セッションID再生成
     *
     * @return bool
     **/
    public function regenerateId(): bool
    {
        $result = session_regenerate_id(true);
        if ($result === false) {
            throw new CacheException(
                "session id regenerate error"
            );
        }
        return true;
    }
}
