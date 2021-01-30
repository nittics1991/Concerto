<?php

/**
*   AbacDecisionPointInterface
*
*   @version 200725
*/

namespace Concerto\gate\abac;

use Concerto\gate\abac\{
    AbacRequestInterface,
    AbacRequestInterface
};

interface AbacDecisionPointInterface
{
    /**
    *   decied
    *
    *   @param AbacRequestInterface $request
    *   @return array [AbacDecision, AbacObligation]
    */
    public function decied(
        AbacRequestInterface $request,
        AbacAttributeInterface $attribute
    ) : array;
}
