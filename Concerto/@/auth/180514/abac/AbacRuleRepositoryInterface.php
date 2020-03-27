<?php

/**
*   AbacRuleRepositoryInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

interface AbacRuleRepositoryInterface
{
    /**
    *   create
    *
    *   @param string
    *   @param string
    **/
    public function create($id, $rule);
    
    /**
    *   delete
    *
    *   @param string
    **/
    public function delete($id);
    
    /**
    *   update
    *
    *   @param string
    *   @param string
    **/
    public function update($id, $rule);
    
    /**
    *   get
    *
    *   @param string
    *   @return string
    **/
    public function get($id);
}
