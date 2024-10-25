<?php

/**
*   EntityTraitInterface
*
*   @version 170308
*/

namespace dev\domain\common;

interface EntityTraitInterface
{
    /**
    *   getId
    *
    *   @return mixed
    */
    public function getId();

    /**
    *   equales
    *
    *   @param EntityTraitInterface
    *   @return bool
    */
    public function equals(EntityTraitInterface $object);
}
