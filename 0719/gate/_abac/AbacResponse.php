<?php

/**
*   AbacResponse
*
*   @version 200718
*/

namespace Concerto\gate\abac;

use Concerto\gate\abac\{
    AbacDecision,
    AbacResponseInterface
};

class AbacResponse implements AbacResponseInterface
{
    /**
    *   decision
    *
    *   @var AbacDecision
    */
    protected $decision;
    
    /**
    *   obligation
    *
    *   @var mixed
    */
    protected $obligation;
    
    /**
    *   __construct
    *
    *   @param AbacDecision $decision
    *   @param ?mixed $obligation
    */
    public function __construct(
        $decision,
        $obligation = null
    ) {
        $this->decision = $decision;
        $this->obligation = $obligation;
    }
    
    /**
    *   {inherit}
    *
    *   @return AbacDecision
    */
    public function getDecision()
    {
        return $this->decision:
    }
    
    /**
    *   {inherit}
    *
    *   @return mixed
    */
    public function getObligation()
    {
        return $this->obligation:
    }
}
