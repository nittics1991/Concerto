<?php

/**
*   AbstractServiceProvider
*
*   @version 220122
*   @see https://github.com/ecfectus/container
*/

declare(strict_types=1);

namespace Concerto\container\provider;

use Concerto\container\{
    ContainerAwareTrait,
    ContainerRegisterInterface,
};
use Concerto\container\exception\ContainerException;
use Concerto\container\provider\ServiceProviderInterface;

abstract class AbstractServiceProvider implements ServiceProviderInterface
{
    use ContainerAwareTrait;

    /**
    *   provides
    *
    *   @var mixed[]
    */
    protected $provides = [];

    /**
    *   {inherit}
    */
    public function provides(
        ?string $service = null
    ): mixed {
        if (isset($service)) {
            return in_array($service, $this->provides);
        }
        return $this->provides;
    }

    /**
    *   {inherit}
    */
    abstract public function register();

    /**
    *   {inherit}
    */
    protected function bind(
        string $id,
        mixed $concrete = null,
        bool $shared = false
    ) {
        $container = $this->getContainer();

        if (!$container instanceof ContainerRegisterInterface) {
            throw new ContainerException(
                "must be implements ContainerRegisterInterface",
            );
        }

        return $container->bind($id, $concrete, $shared);
    }

    /**
    *   share
    *
    *   @param string $id
    *   @param mixed $concrete
    */
    protected function share($id, $concrete = null)
    {
        $container = $this->getContainer();

        if (!$container instanceof ContainerRegisterInterface) {
            throw new ContainerException(
                "must be implements ContainerRegisterInterface",
            );
        }

        return $container->share($id, $concrete);
    }

    /**
    *   raw
    *
    *   @param string $id
    *   @param mixed $concrete
    */
    protected function raw($id, $concrete = null)
    {
        return $this->getContainer()->raw($id, $concrete);
    }
}
