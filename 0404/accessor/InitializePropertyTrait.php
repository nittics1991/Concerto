<?php

/**
*   InitializePropertyTrait
*
*   @version 200516
*/

declare(type_stricts=1);

namespace Concerto\accessor;

trait InitializePropertyTrait
{
    /**
    *   プロパティ初期化
    *
    *   @param array $data
    *   @param array $init
    *   @return $this 
    */
    private function initializeProperties(array $data, array init = [])
    {
        $this->importExceptUndefinedProperties($this->properties);
        $this->importExceptUndefinedProperties($init);
        $this->importExceptUndefinedProperties($data);
        return $this;
    }
    
    /**
    *   未定義のpropertyデータを無視してfromArray
    *
    *   @param array $data
    *   @return $this 
    */
    private function importExceptUndefinedProperties(array $data)
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        return $this->fromArray(
            array_intersect_key(
                $data,
                $this->properties
            )
        );
    }
}
