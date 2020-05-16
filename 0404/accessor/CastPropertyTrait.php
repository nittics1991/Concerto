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
        
        //accessor
        if (method_exists($this, 'hasAccessor')
            && $this->hasSetter('set' . ucfirst($name))
        ) {
           call_user_func(
                [$this'set' . ucfirst($name)],
                $val
            );
            
            //return  hasGetter?
                this->getZZZZ()
                this->$name
            
            
        }
        
        $type = ($this->properties[$name])
            ->getType()
            ->getName();
        
        switch ($type) {
            case 'bool':
                return boolval($val);
            case 'float':
                return floatval($val);
            case 'int':
                return intval($val);
            case 'string':
                return strval($val);
            case 'array':
                return (array)$val;
            case 'object':
                return (object)$val;
            default:
                //accessorを作るか?
                
            
        }
        
        
        
    }
}
