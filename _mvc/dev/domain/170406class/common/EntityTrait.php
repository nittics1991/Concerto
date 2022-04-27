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
    *   {inherit}
    *
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    *   {inherit}
    *
    */
    public function equals(EntityTraitInterface $object)
    {
        return $this->getId() === $object->getId();
    }
}
