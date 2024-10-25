<?php

/**
*   DataContainer
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\ArrayAccessObject;
use InvalidArgumentException;

/**
*   @template TValue
*   @extends ArrayAccessObject<TValue>
*/
abstract class DataContainer extends ArrayAccessObject
{
    /**
    *   @var string[]
    *   @example ['bool_data', 'int_data']
    */
    protected static array $schema = [];

    /**
    *   offsetGet
    *
    *   @param mixed $offset
    *   @return mixed
    */
    public function offsetGet(
        mixed $offset
    ): mixed {
        if (
            ! is_string($offset) &&
            ! is_int($offset)
        ) {
            throw new InvalidArgumentException(
                "must be type int|string",
            );
        }

        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }

        if (in_array($offset, static::$schema)) {
            return null;
        }
            throw new InvalidArgumentException(
                "no property called:{$offset}"
            );
    }

    /**
    *   @inheritDoc
    */
    public function offsetSet(
        mixed $offset,
        mixed $value
    ): void {
        if (
            ! is_string($offset) &&
            ! is_int($offset)
        ) {
            throw new InvalidArgumentException(
                "must be type int|string",
            );
        }

        if (!in_array($offset, static::$schema)) {
            throw new InvalidArgumentException(
                "no property called:{$offset}"
            );
        }
        $this->data[$offset] = $value;
    }

    /**
    *   getInfo
    *
    *   @param ?string $key
    *   @return string[]|string
    */
    public function getInfo(
        ?string $key = null
    ): array|string {
        if (is_null($key)) {
            return static::$schema;
        }

        if (
            ($pos = array_search($key, static::$schema)) !== false
        ) {
            return static::$schema[$pos];
        }
        throw new InvalidArgumentException(
            "no property called:{$key}"
        );
    }
}
