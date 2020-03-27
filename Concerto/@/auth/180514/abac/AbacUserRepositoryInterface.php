<?php

/**
*   AbacUserRepositoryInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

use Concerto\auth\abac\AbacUserInterface;

interface AbacUserRepositoryInterface
{
    /**
    *   create
    *
    *   @param AbacUserInterface
    **/
    public function create(AbacUserInterface $user);
    
    /**
    *   delete
    *
    *   @param string
    **/
    public function delete($id);
    
    /**
    *   update
    *
    *   @param AbacUserInterface
    **/
    public function update(AbacUserInterface $user);
    
    /**
    *   get
    *
    *   @param string
    *   @return AbacUserInterface
    **/
    public function get($id);
}
