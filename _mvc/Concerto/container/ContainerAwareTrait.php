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

trait ContainerAwareTrait
{
    /**
    * @var ContainerInterface
    */
    protected $container;

    /**
    *   {inherit}
    *
    */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
    *   {inherit}
    *
    */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
