<?php

/**
*   Enum
*
*   @version 220124
*/

declare(strict_types=1);

namespace candidate\accessor;

use BadMethodCallException;
use InvalidArgumentException;
use IteratorAggregate;
use ReflectionClass;
use ReflectionObject;
use Stringable ;
use Traversable;

abstract class Enum implements IteratorAggregate
{
    /**
    *   user need to define constants
    */

    /**
    *   name
    *
    *   @var string
    */
    private $name;

    /**
    *   value
    *
    *   @var mixed
    */
    private $value;

    /**
    *   cache
    *
    *   @var mixed[]
    */
    private $cache = [];

    /**
    *   __construct
    *
    *   @param mixed $value
    */
    public function __construct($value)
    {
        $ref = new ReflectionObject($this);
        $this->cache = $ref->getConstants();
        if (($key = array_search($value, $this->cache, true)) === false) {
            throw new InvalidArgumentException("not defined");
        }
        $this->name = $key;
        $this->value = $value;
    }

    /**
    *   __callStatic
    *
    *   @param string $name
    *   @param mixed[] $args
    *   @return mixed
    */
    public static function __callStatic(string $name, array $args)
    {
        $obj = get_called_class();
        $ref = new ReflectionClass($obj);
        $cache = $ref->getConstants();

        if (!in_array($name, array_keys($cache), true)) {
            throw new BadMethodCallException("not defined");
        }
        return new static(constant("{$obj}::{$name}"));
    }

    /**
    *   __toString
    *
    *   @return string
    */
    public function __toString(): string
    {
        if (is_scalar($this->value)) {
            return (string)$this->value;
        }
        if (
            is_object($this->value) &&
            $this->value instanceof Stringable
        ) {
            return $this->value->__toString();
        }
        throw new BadMethodCallException(
            "can not be __toString()"
        );
    }

    /**
    *   getKey
    *
    *   @return mixed
    */
    public function getKey()
    {
        return $this->name;
    }

    /**
    *   getValue
    *
    *   @return mixed
    */
    public function getValue()
    {
        return $this->value;
    }

    /**
    *   getKeys
    *
    *   @return mixed[]
    */
    public function getKeys(): array
    {
        return array_keys($this->cache);
    }

    /**
    *   getValues
    *
    *   @return mixed[]
    */
    public function getValues(): array
    {
        return $this->cache;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getIterator(): Traversable
    {
        foreach ($this->getValues() as $key => $val) {
            yield $key => $val;
        }
    }
}
