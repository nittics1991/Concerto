<?php

/**
*   EntityTrait
*
*   @version 170308
*/

namespace dev\domain\common;

trait EntityTrait
{
    /**
    *   @inheritDoc
    *
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    *   @inheritDoc
    *
    */
    public function equals(EntityTraitInterface $object)
    {
        return $this->getId() === $object->getId();
    }
}
