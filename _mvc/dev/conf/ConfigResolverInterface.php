<?php

/**
*   ConfigResolverInterface
*
*   @version 221223
*/

declare(strict_types=1);

namespace Concerto\conf;

interface ConfigResolverInterface
{
    /**
    *   resolve
    *
    *   @param string $called_controller_class 
    *   @return ?ConfigInterface
    **/
    public function resolve(
        string $called_controller_class,
    );
}
