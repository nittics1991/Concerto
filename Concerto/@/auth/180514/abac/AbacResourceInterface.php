<?php

/**
*   AbacResourceInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

interface AbacResourceInterface
{
    /**
    *   getId
    *
    *   @return string
    **/
    public function getId();
    
    /**
    *   getRule
    *
    *   @return string
    **/
    public function getRule();
    
    /**
    *   setRule
    *
    *   @return string
    **/
    public function setRule($rule);
}
