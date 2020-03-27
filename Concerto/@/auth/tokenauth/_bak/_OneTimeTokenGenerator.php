<?php

/**
*   OneTimeTokenGenerator
*
*   @version 190903
*/

namespace Concerto\auth\onetimeauth;

use Psr\SimpleCache\CacheInterface;
use Concerto\hashing\RandomNumberGenaratorInterface;

class OneTimeTokenGenerator
{
    /**
    *   cache
    *
    *   @var CacheInterface
    */
    protected $cache;
    
    /**
    *   generator
    *
    *   @var RandomNumberGenaratorInterface
    */
    protected $generator;
    
    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    *   @param RandomNumberGenaratorInterface $generator
    **/
    public function __construct(
        CacheInterface $cache,
        RandomNumberGenaratorInterface $generator
    ) {
        $this->cache = $cache;
        $this->generator = $generator;
    }
    
    /**
    *   generate
    *
    *   @param int $ttl
    *   @return string
    **/
    public function generate(int $ttl = 60 * 15): string
    {
        $token = $this->generator->generate();
        $this->cache->set($token, time(), $tttl);
        return $token;
    }
    
    /**
    *   has
    *
    *   @param string $token
    *   @return bool
    **/
    public function has(string $token): bool
    {
        return $this->cache->has($token);
    }
}
