<?php

namespace Concerto\test\delegator;

use Concerto\test\delegator\LibInterface;

class LibClass implements LibInterface
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function nonInjected(string $str)
    {
        return __METHOD__ . $str;
    }
    
    public function injected(LibInterface $obj)
    {
        return __METHOD__ . $obj->id;
    }
    
    public function getLibId()
    {
        return $this->id;
    }
    
    public function extended($str)
    {
        return __METHOD__ . $str;
    }
}
