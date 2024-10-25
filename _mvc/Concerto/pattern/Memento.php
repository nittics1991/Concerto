<?php

/**
*   Memento
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\pattern;

use RuntimeException;

class Memento
{
    /**
    *   @var mixed
    */
    private mixed $storage;

    /**
    *   @var string
    */
    private string $class_name;

    /**
    *   __construct
    *
    *   @param mixed $params
    */
    protected function __construct(
        mixed $params
    ) {
        $class_name = $this->getCalledClassName();

        if (is_null($class_name)) {
            throw new RuntimeException(
                'can not get subclass name'
            );
        }

        $this->storage = $params;

        $this->class_name = $class_name;
    }

    /**
    *   データ取得
    *
    *   @return mixed
    */
    protected function getMemento(): mixed
    {
        $class_name = $this->getCalledClassName();

        if ($class_name === $this->class_name) {
            return $this->storage;
        }

        throw new RuntimeException(
            "mismatch subclass:{$this->class_name} " .
            "given {$class_name}"
        );
    }

    /**
    *   呼び出し元object class取得
    *
    *   @return ?string
    */
    private function getCalledClassName(): ?string
    {
        $backtrace = debug_backtrace();

        if (isset($backtrace[2]['object'])) {
            $object = $backtrace[2]['object'];

            return get_class($object);
        }

        return null;
    }
}
