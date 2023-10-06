<?php

use InvalidArgumentException;

abstract class ValueObject
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __invoke()
    {
        return $this->id;
    }

    public function equal(ValueObject $target): bool
    {
        return $target() === $this->id;
    }

    public function same(ValueObject $target): bool
    {
        return $target() == $this->id;
    }
}
