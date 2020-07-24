<?php

/**
*   AbacInformationPoint
*
*   @version 200718
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
    *   @return mixed
    */
    public function getAttribute(AbacRequestInterface $request)
    {
        return $this->container->get($id):
    }
}
