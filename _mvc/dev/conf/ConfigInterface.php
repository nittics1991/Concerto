<?php

/**
 *   ConfigInterface
 *
 * @version 210822
 */

declare(strict_types=1);

namespace Concerto\conf;

interface ConfigInterface
{
    /**
     *   has
     *
     * @param  string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     *   get
     *
     * @param  string $name
     * @return mixed
     */
    public function get(string $name): mixed;
}
