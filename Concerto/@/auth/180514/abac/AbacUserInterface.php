<?php

/**
*   AbacUserInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

interface AbacUserInterface
{
    /**
    *   getId
    *
    *   @return string
    **/
    public function getId();
    
    /**
    *   attacheAttribute
    *
    *   @param string
    **/
    public function attacheAttribute($attr);
    
    /**
    *   detacheAttribute
    *
    *   @param string
    **/
    public function detacheAttribute($attr);
    
    /**
    *   hasAttribute
    *
    *   @param string
    *   @return bool;
    **/
    public function hasAttribute($attr);
    
    /**
    *   getAttributes
    *
    *   @return array
    **/
    public function getAttributes();
}
