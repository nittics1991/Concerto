<?php

/**
*   IllegalUserCache
*
*   @version 190520
*/

namespace Concerto\auth\authcounter;

use RuntimeException;
use Psr\SimpleCache\CacheInterface;
use Concerto\standard\Server;

class IllegalUserCache
{
    /**
    *   cache
    *
    *   @var CacheInterface
    */
    protected $cache;
    
    /**
    *   ttl
    *
    *   @var int
    */
    protected $ttl;
    
    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    *   @param int $ttl
    **/
    public function __construct(
        CacheInterface $cache,
        int $ttl = 60 * 15
    ) {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }
    
    /**
    *   has
    *
    *   @return bool
    **/
    public function has(): bool
    {
        $id = $this->getId();
        return $this->cache->has($id);
    }
    
    /**
    *   set
    *
    *   @return $this
    **/
    public function set()
    {
        $id = $this->getId();
        if (!$this->cache->has($id)) {
            $this->cache->set($id, time(), $this->ttl);
        }
        return $this;
    }
    
    /**
    *   ID
    *
    *   @return string
    **/
    protected function getId(): string
    {
        $ip = (Server::has('x-forwarded-for')) ?
            Server::get('x-forwarded-for') : Server::get('remote_addr');
        
        $id = mb_ereg_replace('\.', '_', $ip);
        
        if (empty($id)) {
            throw new RuntimeException(
                "could not get IP address"
            );
        }
        return $id;
    }
}
