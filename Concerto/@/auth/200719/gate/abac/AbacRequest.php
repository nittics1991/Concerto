<?php

/**
*   AbacRequestInterface
*
*   @version 200718
*/

namespace Concerto\gate\abac;

use Concerto\gate\abac\AbacRequestInterface;

class AbacRequest implements AbacRequestInterface
{
    /**
    *   subject
    *
    *   @var mixed
    */
    protected $subject;
    
    /**
    *   resource
    *
    *   @var mixed
    */
    protected $resource;
    
    /**
    *   action
    *
    *   @var mixed
    */
    protected $action;
    
    /**
    *   __construct
    *
    *   @param mixed $subject
    *   @param mixed $resource
    *   @param mixed $action
    */
    public function __construct(
        $subject,
        $obligation = null
    ) {
        $this->subject = $subject;
        $this->resource = $resource;
        $this->action = $action;
    }
    
    /**
    *   getSubject
    *
    *   @return mixed
    */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
    *   getResource
    *
    *   @return mixed
    */
    public function getResource()
    {
        return $this->resource;
    }
    
    /**
    *   getAction
    *
    *   @return mixed
    */
    public function getAction()
    {
        return $this->action;
    }
}
