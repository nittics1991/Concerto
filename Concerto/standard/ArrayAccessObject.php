<?php

/**
*   ArrayAccessObject
*
*   @version 210609
*/

declare(strict_types=1);

namespace Concerto\standard;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

class ArrayAccessObject implements ArrayAccess, IteratorAggregate, Countable
{
    /**
    *   データコンテナ
    *
    *   @var array
    */
    protected $data = [];

    /**
    *   {inherit}
    *
    */
    public function __get(string $name): mixed
    {
        return $this->offsetGet($name);
    }

    /**
    *   {inherit}
    *
    */
    public function __set(string $name, mixed $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
    *   {inherit}
    *
    */
    public function __isset(string $name): bool
    {
        return $this->offsetExists($name);
    }

    /**
    *   {inherit}
    *
    */
    public function __unset(string $name): void
    {
        $this->offsetUnset($name);
    }

    /**
    *   {inherit}
    *
    */
    public function offsetGet(mixed $offset): mixed
    {
        return (isset($this->data[$offset])) ?
            $this->data[$offset] : null;
    }

    /**
    *   {inherit}
    *
    */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
    *   {inherit}
    *
    */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
    *   {inherit}
    *
    */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    /**
    *   {inherit}
    *
    */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
    *   {inherit}
    *
    */
    public function count(): int
    {
        return count($this->data);
    }

    /**
    *   データ初期化
    *
    */
    public function unsetAll()
    {
        $this->data = [];
    }

    /**
    *   一括入力
    *
    *   @param mixed[] $array [id => data]
    *   @return object $this
    */
    public function fromArray(array $array)
    {
        if (!is_array($array)) {
            throw new InvalidArgumentException("only array");
        }

        foreach ($array as $key => $val) {
            $this[$key] = $val;
        }
        return $this;
    }

    /**
    *   一括出力
    *
    *   @return mixed[]
    */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
    *   empty
    *
    *   @param ?string $key
    *   @return bool
    */
    public function isEmpty(string $key = null): bool
    {
        if (!is_null($key)) {
            return (isset($this->data[$key])) ?  empty($this->data[$key]) : true;
        }

        foreach ((array)$this->data as $val) {
            if (!empty($val)) {
                return false;
            }
        }
        return true;
    }

    /**
    *   NULL
    *
    *   @param string $key
    *   @return bool
    */
    public function isNull(string $key = null): bool
    {
        if (!is_null($key)) {
            return !isset($this->data[$key]);
        }

        foreach ((array)$this->data as $val) {
            if (!is_null($val)) {
                return false;
            }
        }
        return true;
    }
}
