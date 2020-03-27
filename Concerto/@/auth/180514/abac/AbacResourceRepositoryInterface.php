<?php

/**
*   AbacResourceRepositoryInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

use Concerto\auth\abac\AbacResourceInterface;

interface AbacResourceRepositoryInterface
{
    /**
    *   create
    *
    *   @param AbacResourceInterface
    **/
    public function create(AbacResourceInterface $resource);
    
    /**
    *   delete
    *
    *   @param string
    **/
    public function delete($id);
    
    /**
    *   update
    *
    *   @param AbacResourceInterface
    **/
    public function update(AbacResourceInterface $resource);
    
    /**
    *   get
    *
    *   @param string
    *   @return AbacResourceInterface
    **/
    public function get($id);
}
