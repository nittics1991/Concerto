<?php

/**
*   AbacResponseInterface
*
*   @version 200718
*/

namespace Concerto\gate\abac;

interface AbacResponseInterface
{
    /**
    *   getDecision
    *
    *   @return mixed
    */
    public function getDecision();
    
    /**
    *   getObligation
    *
    *   @return mixed
    */
    public function getObligation();
}
