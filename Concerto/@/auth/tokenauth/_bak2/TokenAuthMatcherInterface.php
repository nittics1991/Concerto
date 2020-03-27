<?php

/**
*   TokenAuthMatcherInterface
*
*   @ver 190903
*/

namespace Concerto\auth\tokenauth;

interface TokenAuthMatcherInterface
{
    /**
    *   match
    *
    *   @param string $token
    *   @return bool
    **/
    public function match(string $token): bool;
}
