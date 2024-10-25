<?php

/**
*   ServiceProviderAggregateInterface
*
*   @version 220512
*   @see https://github.com/thephpleague/container
*/

declare(strict_types=1);

namespace Concerto\container\provider;

use Concerto\container\ContainerAwareInterface;

interface ServiceProviderAggregateInterface extends ContainerAwareInterface
{
    /**
    *   addServiceProvider
    *
    *   @param string $provider
    *   @return mixed
    */
    public function addServiceProvider(
        $provider
    );
}
