<?php

/**
*   ArrayAccessObject
*
*   @version 221214
*/

declare(strict_types=1);

namespace Concerto\standard;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
*   @template TValue
*   @implements ArrayAccess<int|string, TValue>
*   @implements IteratorAggregate<ArrayIterator>
*/
class ArrayAccessObject implements
    ArrayAccess,
    IteratorAggregate,
    Countable
{
    /**
    *   @var mixed[]
    */
    protected array $data = [];

    /**
    *   @inheritDoc
    */
    public function __get(
        string $name
    ): mixed {
        return $this->offsetGet($name);
    }

    /**
    *   @inheritDoc
    */
    public function __set(
        string $name,
        mixed $value
    ): void {
        $this->offsetSet($name, $value);
    }

    /**
    *   @inheritDoc
    */
    public function __isset(
        string $name
    ): bool {
        return $this->offsetExists($name);
    }

    /**
    *   @inheritDoc
    */
    public function __unset(
        string $name
    ): void {
        $this->offsetUnset($name);
    }

    /**
    *   @inheritDoc
    */
    public function offsetGet(
        mixed $offset
    ): mixed {
        return (isset($this->data[$offset])) ?
            $this->data[$offset] : null;
    }

    /**
    *   @inheritDoc
    */
    public function offsetSet(
        mixed $offset,
        mixed $value
    ): void {
        $this->data[$offset] = $value;
    }

    /**
    *   @inheritDoc
    */
    public function offsetExists(
        mixed $offset
    ): bool {
        return isset($this->data[$offset]);
    }

    /**
    *   @inheritDoc
    */
    public function offsetUnset(
        mixed $offset
    ): void {
        unset($this->data[$offset]);
    }

    /**
    *   @inheritDoc
    *
    *   @return ArrayIterator<int|string, mixed>
    */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
    *   @inheritDoc
    */
    public function count(): int
    {
        return count($this->data);
    }

    /**
    *   unsetAll
    *
    *   @return void
    */
    public function unsetAll(): void
    {
        $this->data = [];
    }

    /**
    *   fromArray
    *
    *   @param mixed[] $array [id => data]
    *   @return static
    */
    public function fromArray(
        array $array
    ): static {
        foreach ($array as $key => $val) {
            $this[$key] = $val;
        }
        return $this;
    }

    /**
    *   toArray
    *
    *   @return mixed[]
    */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
    *   isEmpty
    *
    *   @param ?string $key
    *   @return bool
    */
    public function isEmpty(
        ?string $key = null
    ): bool {
        if (!is_null($key)) {
            return (isset($this->data[$key])) ?
                empty($this->data[$key]) : true;
        }

        foreach ((array)$this->data as $val) {
            if (!empty($val)) {
                return false;
            }
        }
        return true;
    }

    /**
    *   isNull
    *
    *   @param ?string $key
    *   @return bool
    */
    public function isNull(
        ?string $key = null
    ): bool {
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
