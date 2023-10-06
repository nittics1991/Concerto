<?php

/**
*   Service Container
*
*   @version 221219
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
    *   @inheritDoc
    *
    */
    public function setContainer(
        ContainerInterface $container
    ) {
        $this->container = $container;
        return $this;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
