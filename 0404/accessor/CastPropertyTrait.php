<?php

/**
*   CastPropertyTrait
*
*   @version 200516
*/

declare(type_stricts=1);

namespace Concerto\accessor;

trait CastPropertyTrait
{
    /**
    *   プロパティで配列を型変換
    *
    *   @param array $data
    *   @return array
    */
    private function castByProperties(array $data): array
    {
        $casted = [];
        foreach ($data as $name => $val) {
            $casted[$name] = $this->castByProperty($name, $val);
            
        );
        return $casted;
    }
    
    /**
    *   プロパティで型変換
    *
    *   @param string $name
    *   @param mixed $val
    *   @return mixed 
    */
    private function castByProperty(string $name, $val)
    {
        if (!$this->has($name)) {
            return $val;
        }
        
        $type = ($this->properties[$name])->
        
        
        
    }
}
