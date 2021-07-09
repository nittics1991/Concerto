<?php

/**
*   EntityTraitInterface
*
*   @version 170308
*/

namespace Concerto\domain\common;

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
