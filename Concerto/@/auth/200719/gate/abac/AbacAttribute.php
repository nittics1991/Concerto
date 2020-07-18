<?php

/**
*   AbacAttribute
*
*   @version 200718
*/

namespace Concerto\gate\abac;

use Concerto\gate\abac\AbacAttributeInterface;

class AbacAttribute implements AbacAttributeInterface
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
    *   environment
    *
    *   @var mixed
    */
    protected $environment;
    
    /**
    *   __construct
    *
    *   @param mixed $subject
    *   @param mixed $resource
    *   @param mixed $environment
    */
    public function __construct(
        $subject,
        $resource,
        $environment
    ) {
        $this->subject = $subject;
        $this->resource = $resource;
        $this->environment = $environment;
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
    *   getEnvironment
    *
    *   @return mixed
    */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
