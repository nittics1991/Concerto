<?php

/**
 *   ConfigReaderArray
 *
 * @version 191210
 */

declare(strict_types=1);

namespace Concerto\conf\conf;

use Concerto\conf\conf\{
    Config,
    ConfigInterface,
    ConfigReaderInterface
};

class ConfigReaderArray implements ConfigReaderInterface
{
    /**
     *   {inherit}
     */
    public function build(string $path): ConfigInterface
    {
        return new Config(include $path);
    }
}
