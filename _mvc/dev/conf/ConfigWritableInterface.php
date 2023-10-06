<?php

/**
 *   ConfigWritableInterface
 *
 * @version 210822
 */

declare(strict_types=1);

namespace Concerto\conf;

interface ConfigWritableInterface
{
    /**
     *   set
     *
     * @param  string $name
     * @param  mixed  $val
     * @return static
     **/
    public function set(
        string $name,
        $val
    ): static;

    /**
     *   remove
     *
     * @param  string $name
     * @return static
     **/
    public function remove(
        string $name
    ): static;
}
