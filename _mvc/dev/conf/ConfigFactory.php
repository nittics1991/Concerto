<?php

/**
*   ConfigFactory
*
*   @version 221223
*/

declare(strict_types=1);

namespace Concerto\conf;

class ConfigFactory
{
    /**
    *    @var ConfigResolverInterface
    */
    private ConfigResolverInterface $resolver;
    
    /**
    *    __construct
    *
    *   @param ConfigResolverInterface $resolver
    */
    public function __construct(
        ConfigResolverInterface $resolver,
    ) {
        $this->resolver = $resolver;
    }

    /**
    *   build
    *
    *   @param string $called_controller_class 
    *   @return ?ConfigInterface
    **/
    public function build(
        string $called_controller_class,
    ): ?ConfigInterface {
        return $this->resolver->resolve(
            $called_controller_class,
        );
    }
}
