<?php

/**
*   AbacRequestInterface
*
*   @version 200718
*/

namespace Concerto\gate\abac;

interface AbacRequestInterface
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
    *   getAction
    *
    *   @return mixed
    */
    public function getAction();
}
