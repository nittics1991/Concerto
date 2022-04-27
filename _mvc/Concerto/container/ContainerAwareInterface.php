<?php

/**
*   Service Container
*
*   @version 220122
*   @see https://github.com/ecfectus/container
*/

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
    */
    public function setContainer(ContainerInterface $container);

    /**
    *   getContainer
    *
    *   @return ContainerInterface
    */
    public function getContainer(): ContainerInterface;
}
