<?php

use ValueObject;

abstract class DomainValidator
{
    abstract public static function validate($value): bool;

    public static function __callStatic($name, array $args)
    {
        return $this->validate(array_first($args));
    }
}
