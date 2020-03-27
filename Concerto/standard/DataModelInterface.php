<?php

/**
*   DataModelInterface
*
*   @ver 190523
*/

namespace Concerto\standard;

interface DataModelInterface
{
    /**
    *   toArray
    *
    *   @return array
    **/
    public function toArray();
    
    /**
    *   getInfo
    *
    *   @param ?string $key
    *   @return mixed
    **/
    public function getInfo($key = null);
}
