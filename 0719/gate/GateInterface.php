<?php

/**
*   GateInterface
*
*   @version 200718
*/

namespace Concerto\gate;

interface GateInterface
{
    /**
    *   allowed
    *
    *   @param mixed $id
    *   @param mixed $context
    *   @return bool
    */
    public function allowed($id, $context):bool;
    
    /**
    *   denied
    *
    *   @param mixed $id
    *   @param mixed $context
    *   @return bool
    */
    public function denied($id, $context):bool;
    
    /**
    *   judge
    *
    *   @param mixed $id
    *   @param mixed $context
    *   @return mixed
    * 
    */
    public function judge($id, $context);
}
