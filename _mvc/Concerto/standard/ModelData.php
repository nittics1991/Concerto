<?php

/**
*   データベーステーブルデータ
*
*   @version 210609
*/

declare(strict_types=1);

namespace Concerto\standard;

use DateTime;
use InvalidArgumentException;
use Concerto\standard\DataContainerValidatable;
use Concerto\standard\DataModelInterface;

class ModelData extends DataContainerValidatable implements
    DataModelInterface
{
    /**
    *   gettype判定用定数
    *
    */
    public const BOOLEAN = 'boolean';
    public const INTEGER = 'integer';
    public const FLOAT = 'double';
    public const DOUBLE = 'double';
    public const STRING = 'string';
    public const DATETIME = 'datetime';

    /**
    *   カラム情報(overwrite)
    *
    *   @var string[]
    *
    *   @example ['bool_data' => parent::BOOLEAN,
    *                               'int_data' => parent::INTEGER]
    */
    protected static $schema = [];

    /**
    *   カラム名エイリアス(overwrite)
    *
    *   @var string[]
    *
    *   @example ['nm_tanto' => 'tanto_code']
    */
    protected static $alias = [];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(array $params = [])
    {
        $this->fromArray($params);
    }

    /**
    *   {inherit}
    *
    *   @throws InvalidArgumentException
    */
    public function offsetGet(mixed $offset): mixed
    {
        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
        }

        if (array_key_exists($offset, static::$schema)) {
            return null;
        }

        if (array_key_exists($offset, static::$alias)) {
            $name = static::$alias[$offset];
            return $this->offsetGet($name);
        }
        throw new InvalidArgumentException("no property called:{$offset}");
    }

    /**
    *   {inherit}
    *
    *   @throws InvalidArgumentException
    */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (array_key_exists($offset, static::$alias)) {
            $name = static::$alias[$offset];
            $this->__set($name, $value);
            return;
        }

        if (!array_key_exists($offset, static::$schema)) {
            throw new InvalidArgumentException("no property called:{$offset}");
        }

        $schema = static::$schema[$offset];
        $type = gettype($value);

        if ($value === null) {
            $this->data[$offset] = $value;
            return;
        }

        if ($schema === self::DATETIME) {
            if ($value instanceof DateTime) {
                $this->data[$offset] = $value;
            } else {
                $this->data[$offset] = new DateTime($value);
            }
            return;
        }

        if ($type === $schema) {
            $this->data[$offset] = $value;
            return;
        }

        switch ($schema) {
            case self::BOOLEAN:
                $this->data[$offset] = (bool)$value;
                return;
            case self::INTEGER:
                $this->data[$offset] = (int)$value;
                return;
            case self::FLOAT:
                $this->data[$offset] = (float)$value;
                return;
            case self::DOUBLE:
                $this->data[$offset] = (double)$value;
                return;
            case self::STRING:
                $this->data[$offset] = (string)$value;
                return;
            default:
                $this->data[$offset] = (string)$value;
                return;
        }
    }

    /**
    *   {inherit}
    *
    */
    public function offsetExists(mixed $offset): bool
    {
        if (array_key_exists($offset, static::$alias)) {
            $name = static::$alias[$offset];
            return isset($this->$name);
        }
        return isset($this->data[$offset]);
    }

    /**
    *   カラム情報
    *
    *   @param ?string $key
    *   @return string[]|string
    *   @throws InvalidArgumentException
    */
    public function getInfo($key = null)
    {
        if (is_null($key)) {
            return static::$schema;
        }

        if (array_key_exists($key, static::$schema)) {
            return static::$schema[$key];
        }

        if (array_key_exists($key, static::$alias)) {
            $name = static::$alias[$key];
            return $this->getInfo($name);
        }
        throw new InvalidArgumentException("no property called:{$key}");
    }

    /**
    *   バリデート
    *
    *   @return bool
    */
    public function isValid(): bool
    {
        $this->valid = [];

        if (empty($this->data)) {
            return true;
        }

        $result = true;

        foreach ($this->data as $key => $val) {
            if (!array_key_exists($key, static::$schema)) {
                $this->valid[$key][] = 'does not exists in schema';
                $result = false;
            }

            $result = $this->validCom((string)$key, $val) && $result;
            $result = $this->validCustom((string)$key, $val) && $result;
        }
        return $result;
    }
}
