<?php

/**
*   AbacAttributeRepositoryInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

interface AbacAttributeRepositoryInterface
{
    /**
    *   create
    *
    *   @param string
    *   @param string
    **/
    public function create($id, $attr);
    
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
    public function update($id, $attr);
    
    /**
    *   get
    *
    *   @param string
    *   @return string
    **/
    public function get($id);
}
