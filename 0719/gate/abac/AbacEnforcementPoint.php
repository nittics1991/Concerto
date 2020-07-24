<?php

/**
*   AbacEnforcementPoint
*
*   @version 200725
*/

namespace Concerto\gate\abac;

use Psr\container\ContainerInterface;

use Concerto\gate\abac\AbacEnforcementPointInterface;

class AbacEnforcementPoint implements AbacEnforcementPointInterface
{
    /**
    *   container
    *
    *   @var ContainerInterface
    */
    protected $container;
    
    /**
    *   __construct
    *
    *   @param ContainerInterface $container
    */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }
    
    /**
    *   {inherit}
    *
    *   @return bool
    */
    public function allowed(
        $action,
        array ...$arguments = []
    ) :bool {
        $request = $this->buildRequest($action, $arguments);
        
        $informationPoint = $this->container->get("abac.pip.{$action}");
        $attribute = $informationPoint->getAttribute($request);
        
        $decisionPoint = $this->container->get("abac.pip.{$action}");
        
        list($decision, $obligation) =
            $decisionPoint->decied($request, $attribute);
        
        call_user_func($obligation, $request, $attribute, $decision);
        
        return $decision->getKey() == AbacDecision::PERMIT;
    }
    
    /**
    *   {inherit}
    *
    *   @return bool
    */
    public function denied(
        $action,
        array ...$arguments = []
    ) :bool {
        return !$this->allowed($action, $arguments);
    }
}
