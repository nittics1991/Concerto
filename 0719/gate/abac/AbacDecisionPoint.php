<?php

/**
*   AbacDecisionPoint
*
*   @version 200725
*/

namespace Concerto\gate\abac;

use Psr\container\ContainerInterface;

use Concerto\gate\abac\AbacInformationPointInterface;

class AbacDecisionPoint implements AbacDecisionPointInterface
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
    *   @param AbacRequestInterface $request
    *   @return array [AbacDecision, AbacObligation]
    */
    public function decied(
        AbacRequestInterface $request,
        AbacAttributeInterface $attribute
    ) : {
        $dicider = $this->container->get($request->getAction().'.decider');
        $decison = call_user_func($dicider, $request, $attribute);
        
        $obligation = $this->container->get($request->getAction().'.obligation');
        
        return [$decison, $obligation];
    }
}
