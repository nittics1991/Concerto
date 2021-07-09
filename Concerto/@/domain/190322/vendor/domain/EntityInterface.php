<?php

interface EntityInterface
{
    public function uuid();
    public function equals(EntityInterface $target): bool;
}
