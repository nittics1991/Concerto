<?php

/**
*   AbacAttributeInterface
*
*   @version 200718
*/

namespace Concerto\gate\abac;

interface AbacAttributeInterface
{
    /**
    *   getSubject
    *
    *   @return mixed
    */
    public function getSubject();
    
    /**
    *   getResource
    *
    *   @return mixed
    */
    public function getResource();
    
    /**
    *   getEnvironment
    *
    *   @return mixed
    */
    public function getEnvironment();
}
