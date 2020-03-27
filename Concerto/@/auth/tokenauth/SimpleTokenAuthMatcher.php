<?php

/**
*   SimpleTokenAuthMatcher
*
*   @ver 190903
*/

namespace Concerto\auth\tokenauth;

use Psr\SimpleCache\CacheInterface;
use Concerto\auth\tokenauth\TokenAuthMatcherInterface;

class SimpleTokenAuthMatcher implements TokenAuthMatcherInterface
{
    /**
    *   cache
    *
    *   @var CacheInterface
    */
    protected $cache;
    
    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    **/
    public function __construct(
        CacheInterface $cache
    ) {
        $this->cache = $cache;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function match(string $token): bool
    {
        return $this->cache->has($token);
    }
}
