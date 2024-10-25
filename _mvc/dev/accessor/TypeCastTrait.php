<?php

/**
*   TypeCastTrait
*
*   @version 190516
*/

declare(strict_types=1);

namespace candidate\accessor;

use DateTimeImmutable;
use DateTimeInterface;
use LogicException;

trait TypeCastTrait
{
    /**
    *   property type definition
    *
    *   @var array
    *   @warning implemention of the property is mondatory
    *   @example [
    *       'prop1' => 'datetime', 'prop2' => 'bool',
    *       'prop3' => 'dateformat:Y-m-d H:i:s'
    *       'prop4' => 'callable:setProp4'
    *       ]
    */
    // protected $setCastTypes = [];
    // protected $getCastTypes = [];

    /**
    *   @inheritDoc
    *
    */
    public function hasSetCastType(?string $name = null)
    {
        return isset($name) ?
            array_key_exists($name, $this->setCastTypes) :
            $this->setCastTypes;
    }

    /**
    *   @inheritDoc
    *
    */
    public function hasGetCastType(?string $name = null)
    {
        return isset($name) ?
            array_key_exists($name, $this->getCastTypes) :
            $this->getCastTypes;
    }

    /**
    *   setCastTypesの定義取得
    *
    *   @param string $name
    *   @return string
    */
    protected function setCastType(string $name): string
    {
        if (!$this->hasSetCastType($name)) {
            throw new LogicException(
                "setCastTypes not defined:{$name}"
            );
        }
        return $this->setCastTypes[$name];
    }

    /**
    *   getCastTypesの定義取得
    *
    *   @param string $name
    *   @return string
    */
    protected function getCastType(string $name): string
    {
        if (!$this->hasGetCastType($name)) {
            throw new LogicException(
                "getCastTypes not defined:{$name}"
            );
        }
        return $this->getCastTypes[$name];
    }

    /**
    *   toCastDataType
    *
    *   @param string $type
    *   @param mixed $value
    *   @return mixed
    */
    protected function toCastDataType($type, $value)
    {
        switch ($type) {
            case 'bool':
                return (bool)$value;
            case 'int':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'array':
                return (array)$value;
            case 'object':
                return (object)$value;
            case 'binary':
                return (binary)$value;
            case 'null':
                return null;
            case 'datetime':
                if ($value instanceof DateTimeInterface) {
                    return $value;
                }
                return new DateTimeImmutable((string)$value);
            case 'date':
                if ($value instanceof DateTimeInterface) {
                    return $value->modify('today');
                }
                return (new DateTimeImmutable((string)$value))
                    ->modify('today');
        }

        $splited = mb_split(':', $type, 2);
        switch ($splited[0]) {
            case 'dateformat':
                if ($value instanceof DateTimeInterface) {
                    return $value->format($splited[1]);
                }
                return (new DateTime((string)$value))->format($splited[1]);
            case 'callable':
                return call_user_func([$this, $splited[1]], $value);
        }

        throw new LogicException(
            "not defined type:{$type}"
        );
    }
}
