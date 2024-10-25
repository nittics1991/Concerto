<?php

/**
*   Service Container
*
*   @ver 170210
*   @see https://github.com/ecfectus/container
**/

declare(strict_types=1);

namespace Concerto\container;

use Psr\Container\ContainerInterface;

interface ContainerAwareInterface
{
    /**
    *   setContainer
    *
    * @param ContainerInterface $container
    * @return mixed
    **/
    public function setContainer(ContainerInterface $container);
    
    /**
    *   getContainer
    *
    *   @return mixed
    **/
    public function getContainer();
}
