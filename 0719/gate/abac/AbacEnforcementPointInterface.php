<?php

/**
*   AbacEnforcementPointInterface
*
*   @version 200725
*/

namespace Concerto\gate\abac;

interface AbacEnforcementPointInterface
{
    /**
    *   allowed
    *
    *   @param mixed $action
    *   @param ?array ...$arguments [$subject, $resource, $context1, ...]
    *   @return bool
    */
    public function allowed(
        $action,
        ?array ...$arguments = []
    ) :bool;
    
    /**
    *   denied
    *
    *   @param mixed $action
    *   @param ?array ...$arguments [$subject, $resource, $context1, ...]
    *   @return bool
    */
    public function denied(
        $action,
        ?array ...$arguments
    ) :bool;
}
