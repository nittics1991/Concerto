<?php

/**
 *   ConfigReaderInterface
 *
 * @version 210608
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
    * @var mixed[]
    */
    protected $container;

    /**
    *   __construct
    *
    * @param mixed[] $config
    */
    public function __construct(array $config)
    {
        $this->container = $config;
    }

    /**
    *   @inheritDoc
    */
    public function has(string $name): bool
    {
        return ArrayDot::has($this->container, $name);
    }

    /**
    *   @inheritDoc
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
    *   @inheritDoc
    */
    public function set(string $name, $value): ConfigInterface
    {
        $container = ArrayDot::set($this->container, $name, $value);
        return new self($container);
    }

    /**
    *   @inheritDoc
    */
    public function remove(string $name): ConfigInterface
    {
        $container = ArrayDot::remove($this->container, $name);
        return new self($container);
    }

    /**
    *   @inheritDoc
    */
    public function toArray(): array
    {
        return $this->container;
    }

    /**
    *   @inheritDoc
    */
    public function offsetExists(mixed $offset): bool
    {
         return $this->has($offset);
    }

    /**
    *   @inheritDoc
    */
    public function offsetGet(mixed $offset): mixed
    {
         return $this->get($offset);
    }

    /**
    *   @inheritDoc
    */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException(
            "unsupported method:offsetSet"
        );
    }

    /**
    *   @inheritDoc
    */
    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException(
            "unsupported method:offsetUnset"
        );
    }

    /**
    *   replace
    *
    * @param ConfigInterface $config
    * @return ConfigInterface
    */
    public function replace(ConfigInterface $config): ConfigInterface
    {
        $container = array_replace_recursive(
            $this->container,
            $config->toArray()
        );
        return new self($container);
    }
}
