<?php

abstract class EntityId
{
    private $id;

    private $uuid;

    public function __construct($id)
    {
        $this->id = $id;
        $this->uuid = random_bytes(32);
    }

    public function __invoke()
    {
        return $this->id;
    }

    public function uuid()
    {
        return $this->uuid;
    }

    public function equal(EntityId $target): bool
    {
        return $target->uuid() === $this->uuid;
    }

    public function same(EntityId $target): bool
    {
        return $target() === $this->id;
    }
}
