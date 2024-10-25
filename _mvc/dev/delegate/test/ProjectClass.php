<?php

namespace Concerto\test\delegator;

use Concerto\test\delegator\ProjectInterface;

class ProjectClass implements ProjectInterface
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function nonInjected(string $str)
    {
        var_dump(__METHOD__, $str);
        return $str;
    }
    
    public function injected(MyInterface $obj)
    {
        var_dump(__METHOD__, $obj->id);
        return $this->id;
    }
    
    //convertOriginalで使う
    public function getMyId()
    {
        var_dump(__METHOD__);
        return $this->id;
    }
}
