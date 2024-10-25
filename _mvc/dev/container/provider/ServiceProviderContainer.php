<?php

/**
*   Service Container
*
*   @ver 170714
*   @see https://github.com/ecfectus/container
**/

declare(strict_types=1);

namespace Concerto\container\provider;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Concerto\container\ContainerAwareInterface;
use Concerto\container\ContainerAwareTrait;
use Concerto\container\ServiceContainer;
use Concerto\container\provider\BootableServiceProviderInterface;
use Concerto\container\provider\ServiceProviderInterface;
use Concerto\container\exception\NotFoundException;

class ServiceProviderContainer extends ServiceContainer implements
    ContainerInterface,
    ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    /**
    *   booted
    *
    *   @var bool
    **/
    protected $booted = false;
    
    /**
    *   providers
    *
    *   @var array
    **/
    protected $providers = [];
    
    /**
    *   provides
    *
    *   @var array
    **/
    protected $provides = [];
    
    /**
    *   addServiceProvider
    *
    *   @param string $provider
    **/
    public function addServiceProvider($provider)
    {
        $instance = ($this->getContainer()->has($provider)) ?
            $this->getContainer()->get($provider) : new $provider();
        
        $this->providers[$provider] = $instance;
        
        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->getContainer());
        }
        
        //boot後に追加されたProviderのboot()を実行
        if (
            $this->booted
            && $instance instanceof BootableServiceProviderInterface
        ) {
            $instance->boot();
        }
        
        if ($instance instanceof ServiceProviderInterface) {
            foreach ($instance->provides() as $service) {
                $this->provides[$service] = $provider;
            }
            return $this;
        }
        
        throw new InvalidArgumentException(
            "must be a fully qualified class name or instance of :"
            . ServiceProviderInterface::class
        );
    }
    
    /**
    *   bootServiceProviders
    *
    **/
    public function bootServiceProviders()
    {
        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableServiceProviderInterface) {
                $provider->boot();
            }
        }
        $this->booted = true;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException(
                "{$id} is not an existing class"
            );
        }
        $provider = $this->provides[$id];
        $instance = $this->providers[$provider];
        
        //register into the main container.
        //this instance will never be called again so we could destroy
        //it if we wanted to @TODO
        $instance->register();

        //should be registered so lets go back to the main container
        //and fetch it
        return $this->getContainer()->get($id);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function has($id)
    {
        return array_key_exists($id, $this->provides);
    }
}
