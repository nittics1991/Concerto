<?php

use EntityInterface;

trait EntityIdTrait implements EntityInterface
{
    protected $uuid;

    protected function generateId()
    {
        $this->uuid = random_bytes(32);
    }

    public function uuid()
    {
        return $this->uuid;
    }

    public function equals(EntityInterface $target): bool
    {
        return $target->uuid() === $this->uuid;
    }
}
