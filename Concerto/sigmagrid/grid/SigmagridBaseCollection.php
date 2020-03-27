<?php

/**
*   SigmagridBaseCollection
*
*   @version 180906
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use ArrayObject;
use Concerto\standard\Validatable;

class SigmagridBaseCollection extends ArrayObject implements Validatable
{
    /**
    *   valid
    *
    *   @var array
    **/
    protected $valid = [];
    
    /**
    *   {inherit}
    **/
    public function isValid()
    {
        $this->valid = [];
        $cnt = 0;
        
        $this->isValidCom();
        
        foreach ($this as $obj) {
            $result = $obj->isValid();
            if ($result !== true) {
                $this->valid[$cnt] = $obj->getValidError();
            }
            $cnt++;
        }
        return (count($this->valid) == 0);
    }
    
    /*
    *   isValidCom(over write)
    *
    **/
    public function isValidCom()
    {
    }
    
    /**
    *   getValidError
    *
    *   @return array
    **/
    public function getValidError()
    {
        return $this->valid;
    }
    
    /**
    *   toArray
    *
    *   @return array
    **/
    public function toArray()
    {
        $array = [];
        foreach ($this as $obj) {
            $array[] = $obj;
        }
        return $array;
    }
}
