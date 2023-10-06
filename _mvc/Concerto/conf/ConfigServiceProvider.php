<?php

/**
*   ConfigServiceProvider
*
*   @version 221206
*/

declare(strict_types=1);

namespace Concerto\conf;

use Concerto\conf\{
    Config,
    ConfigReaderArray
};
use Concerto\container\provider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'configSystemPath',
        'configSystem',
    ];

    /**
    *   register
    *
    */
    public function register(): void
    {
        $this->share(
            'configSystemPath',
            realpath(
                __DIR__ . '/../../../_config/common/system.php'
            )
        );

        $this->share(
            'configSystem',
            function ($container) {
                return new Config(
                    new ConfigReaderArray(
                        $container->get('configSystemPath')
                    )
                );
            }
        );
    }
}
