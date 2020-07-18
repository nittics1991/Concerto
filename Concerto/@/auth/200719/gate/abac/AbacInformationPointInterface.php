<?php

/**
*   AbacInformationPointInterface
*
*   @version 200718
*/

namespace Concerto\gate\abac;

interface AbacInformationPointInterface
{
    /**
    *   getAttribute
    *
    *   @param AbacRequestInterface $request
    *   @return AbacAttributeInterface
    */
    public function getAttribute(AbacRequestInterface $request)
        :AbacAttributeInterface;
}
