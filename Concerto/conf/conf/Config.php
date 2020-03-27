<?php

/**
 *   ConfigReaderInterface
 *
 * @version 191210
 */

declare(strict_types=1);

namespace Concerto\conf\conf;

use ArrayAccess;
use BadMethodCallException;
use RuntimeException;
use Concerto\arrays\ArrayDot;
use Concerto\conf\conf\ConfigInterface;

class Config implements ArrayAccess, ConfigInterface
{
    /**
     *   container
     *
     * @var array
     */
    protected $container;
    
    /**
     *   __construct
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->container = $config;
    }
    
    /**
     *   {inherit}
     */
    public function has(string $name): bool
    {
        return ArrayDot::has($this->container, $name);
    }
    
    /**
     *   {inherit}
     */
    public function get(string $name)
    {
        if (!$this->has($name)) {
            throw new RuntimeException(
                "not defind:{$name}"
            );
        }
        return ArrayDot::get($this->container, $name);
    }
    
    /**
     *   {inherit}
     */
    public function set(string $name, $value): ConfigInterface
    {
        $container = ArrayDot::set($this->container, $name, $value);
        return new static($container);
    }
    
    /**
     *   {inherit}
     */
    public function remove(string $name): ConfigInterface
    {
        $container = ArrayDot::remove($this->container, $name);
        return new static($container);
    }
    
    /**
     *   {inherit}
     */
    public function toArray(): array
    {
        return $this->container;
    }
    
    /**
     *   {inherit}
     */
    public function offsetExists($name)
    {
         return $this->has($name);
    }
    
    /**
     *   {inherit}
     */
    public function offsetGet($name)
    {
         return $this->get($name);
    }
    
    /**
     *   {inherit}
     */
    public function offsetSet($name, $value)
    {
        throw new BadMethodCallException(
            "unsupported method:offsetSet"
        );
    }
    
    /**
     *   {inherit}
     */
    public function offsetUnset($name)
    {
        throw new BadMethodCallException(
            "unsupported method:offsetUnset"
        );
    }
    
    /**
     *   replace
     *
     * @param  ConfigInterface $config
     * @return ConfigInterface
     */
    public function replace(ConfigInterface $config): ConfigInterface
    {
        $container = array_replace_recursive(
            $this->container,
            $config->toArray()
        );
        return new static($container);
    }
}
