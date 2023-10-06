<?php

/**
*   Enum
*
*   @version 190517
*/

/*
namespace dev\accessor;

use \BadMethodCallException;
use \InvalidArgumentException;
use \IteratorAggregate;
use \ReflectionClass;
use \ReflectionObject;
use \Traversable;
*/

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
    *   @var array
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
    *   @param array $args
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
        return (string)$this->value;
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
    *   @return array
    */
    public function getKeys(): array
    {
        return array_keys($this->cache);
    }

    /**
    *   getValues
    *
    *   @return array
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
