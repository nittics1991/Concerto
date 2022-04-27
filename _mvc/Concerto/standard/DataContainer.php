<?php

/**
*   データコンテナ
*
*   @version 210609
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\ArrayAccessObject;
use InvalidArgumentException;

abstract class DataContainer extends ArrayAccessObject
{
    /**
    *   カラム情報(overwrite)
    *
    *   @var string[]
    *   @example ['bool_data', 'int_data']
    */
    protected static $schema = [];

    /**
    *   {inherit}
    *
    */
    public function offsetGet(mixed $offset): mixed
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }

        if (in_array($offset, static::$schema)) {
            return null;
        }
        throw new InvalidArgumentException("no property called:{$offset}");
    }

    /**
    *   {inherit}
    *
    */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!in_array($offset, static::$schema)) {
            throw new InvalidArgumentException("no property called:{$offset}");
        }
        $this->data[$offset] = $value;
    }

    /**
    *   カラム情報
    *
    *   @param ?string $key
    *   @return mixed
    */
    public function getInfo(?string $key = null)
    {
        if (is_null($key)) {
            return static::$schema;
        }

        if (($pos = array_search($key, static::$schema)) !== false) {
            return static::$schema[$pos];
        }
        throw new InvalidArgumentException("no property called:{$key}");
    }
}
