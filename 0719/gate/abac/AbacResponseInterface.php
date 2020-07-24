<?php

/**
*   AbacResponseInterface
*
*   @version 200725
*/

namespace Concerto\gate\abac;

interface AbacResponseInterface
{
    /**
    *   getDecision
    *
    *   @return AbacDecision
    */
    public function getDecision():AbacDecision;
    
    /**
    *   getObligation
    *
    *   @return mixed
    */
    public function getObligation();
}
