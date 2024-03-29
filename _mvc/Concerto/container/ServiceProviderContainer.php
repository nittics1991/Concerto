<?php

/**
*   Service Container
*
*   @version 221219
*   @see https://github.com/ecfectus/container
*/

declare(strict_types=1);

namespace Concerto\container;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Concerto\container\{
    ContainerAwareInterface,
    ContainerAwareTrait,
    ServiceContainer
};
use Concerto\container\exception\{
    ContainerException,
    NotFoundException,
};
use Concerto\container\provider\{
    BootableServiceProviderInterface,
    ServiceProviderInterface,
    ServiceProviderAggregateInterface,
};

class ServiceProviderContainer extends ServiceContainer implements
    ContainerInterface,
    ContainerAwareInterface,
    ServiceProviderAggregateInterface
{
    use ContainerAwareTrait;

    /**
    *   @var bool
    */
    protected bool $booted = false;

    /**
    *   @var mixed[]
    */
    protected array $providers = [];

    /**
    *   @var mixed[]
    */
    protected array $provides = [];

    /**
    *   addServiceProvider
    *
    *   @param string $provider
    *   @return mixed
    */
    public function addServiceProvider(
        $provider
    ) {
        $instance = ($this->getContainer()->has($provider)) ?
            $this->getContainer()->get($provider) :
            new $provider();

        $this->providers[$provider] = $instance;

        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->getContainer());
        }

        //boot後に追加されたProviderのboot()を実行
        if (
            $this->booted &&
            $instance instanceof BootableServiceProviderInterface
        ) {
            $instance->boot();
        }

        if ($instance instanceof ServiceProviderInterface) {
            $provides = $instance->provides(null);

            if (is_array($provides)) {
                foreach ($provides as $service) {
                    $this->provides[$service] = $provider;
                }
                return $this;
            }
        }

        throw new InvalidArgumentException(
            "must be a fully qualified class name or instance of :" .
            ServiceProviderInterface::class
        );
    }

    /**
    *   @inheritDoc
    */
    public function bootServiceProviders(): void
    {
        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableServiceProviderInterface) {
                $provider->boot();
            }
        }
        $this->booted = true;
    }

    /**
    *   @inheritDoc
    */
    public function get(
        $id
    ) {
        if (!$this->has($id)) {
            throw new NotFoundException(
                "{$id} is not an existing class"
            );
        }
        $provider = $this->provides[$id];
        $instance = $this->providers[$provider];

        if (!$instance instanceof ServiceProviderInterface) {
            throw new ContainerException(
                "must be implements ServiceProviderInterface",
            );
        }

        //register into the main container.
        //this instance will never be called again so we could destroy
        //it if we wanted to @TODO
        $instance->register();

        //should be registered so lets go back to the main container
        //and fetch it
        return $this->getContainer()->get($id);
    }

    /**
    *   @inheritDoc
    */
    public function has(
        string $id
    ): bool {
        return array_key_exists($id, $this->provides);
    }
}
