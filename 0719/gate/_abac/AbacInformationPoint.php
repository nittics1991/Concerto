<?php

/**
*   AbacInformationPoint
*
*   @version 200725
*/

namespace Concerto\gate\abac;

use Psr\container\ContainerInterface;

use Concerto\gate\abac\AbacInformationPointInterface;

class AbacInformationPoint implements AbacInformationPointInterface
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
    *   @return AbacAttributeInterface
    */
    public function getAttribute(
        AbacRequestInterface $request
    ) :AbacAttributeInterface {
        $repository = $this->container->get($request->getAction());
        
        $attribute = $this->container->get(AbacAttributeInterface::class);
        $attribute->setSubject($repository->getSubject($request));
        $attribute->setResource($repository->getResource($request));
        $attribute->setEnvironment($repository->getEnvironment($request));
        
        return $attribute;
    }
}
