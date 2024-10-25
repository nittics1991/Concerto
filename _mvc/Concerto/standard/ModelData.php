<?php

/**
*   データベーステーブルデータ
*
*   @version 240826
*/

declare(strict_types=1);

namespace Concerto\standard;

use DateTime;
use InvalidArgumentException;
use Concerto\standard\{
    DataContainerValidatable,
    DataModelInterface
};

/**
*   @template TValue
*   @extends DataContainerValidatable<TValue>
*/
class ModelData extends DataContainerValidatable implements
    DataModelInterface
{
    /**
    *   @var string
    */
    public const BOOLEAN = 'boolean';
    public const INTEGER = 'integer';
    public const FLOAT = 'double';
    public const DOUBLE = 'double';
    public const STRING = 'string';
    public const DATETIME = 'datetime';

    /**
    *   @var string[]
    *   @example ['bool_data' => parent::BOOLEAN,
    *             'int_data' => parent::INTEGER]
    */
    protected static array $schema = [];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(
        array $params = []
    ) {
        $this->fromArray($params);
    }

    /**
    *   @inheritDoc
    *
    *   @throws InvalidArgumentException
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

        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
        }

        if (array_key_exists($offset, static::$schema)) {
            return null;
        }

        throw new InvalidArgumentException(
            "no property called:{$offset}"
        );
    }

    /**
    *   @inheritDoc
    *
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

        if (!array_key_exists($offset, static::$schema)) {
            throw new InvalidArgumentException(
                "no property called:{$offset}"
            );
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
            } elseif (is_scalar($value)) {
                $this->data[$offset] =
                    new DateTime(strval($value));
            } else {
                $this->data[$offset] =
                    new DateTime();
            }
            return;
        }

        if ($type === $schema) {
            $this->data[$offset] = $value;
            return;
        }

        if (is_object($value)) {
            $value = method_exists($value, '__toString') ?
                $value->__toString() :
                print_r($value, true);
        }

        switch ($schema) {
            case self::BOOLEAN:
                $this->data[$offset] = boolval($value);
                return;
            case self::INTEGER:
                $this->data[$offset] = intval($value);
                return;
            case self::FLOAT:
                $this->data[$offset] = floatval($value);
                return;
            case self::DOUBLE:
                $this->data[$offset] = floatval($value);
                return;
            case self::STRING:
                $this->data[$offset] = strval($value);
                return;
            default:
                $this->data[$offset] = strval($value);
                return;
        }
    }

    /**
    *   @inheritDoc
    *
    */
    public function offsetExists(
        mixed $offset
    ): bool {
        return isset($this->data[$offset]);
    }

    /**
    *   @inheritDoc
    */
    public function getInfo(
        ?string $key = null
    ): array|string {
        if (is_null($key)) {
            return static::$schema;
        }

        if (array_key_exists($key, static::$schema)) {
            return static::$schema[$key];
        }

        throw new InvalidArgumentException(
            "no property called:{$key}"
        );
    }

    /**
    *   isValid
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

            $result = $this->validCom((string)$key, $val) &&
                $result;

            $result = $this->validCustom((string)$key, $val) &&
                $result;
        }
        return $result;
    }
}
