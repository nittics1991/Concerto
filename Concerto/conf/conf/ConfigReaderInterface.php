<?php

/**
 *   ConfigReaderInterface
 *
 * @version 191210
 */

declare(strict_types=1);

namespace Concerto\conf\conf;

interface ConfigReaderInterface
{
    /**
     *   build
     *
     * @param  string $path
     * @return ConfigInterface
     */
    public function build(string $path): ConfigInterface;
}
