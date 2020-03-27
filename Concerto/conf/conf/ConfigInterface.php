<?php

/**
 *   ConfigReaderInterface
 *
 * @version 191210
 */

declare(strict_types=1);

namespace Concerto\conf\conf;

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
    public function get(string $name);
    
    /**
     *   set
     *
     * @param  string $name
     * @param  mixed  $value
     * @return ConfigInterface
     */
    public function set(string $name, $value): ConfigInterface;
    
    /**
     *   remove
     *
     * @param  string $name
     * @return ConfigInterface
     */
    public function remove(string $name): ConfigInterface;
    
    /**
     *   toArray
     *
     * @return array
     */
    public function toArray(): array;
}
