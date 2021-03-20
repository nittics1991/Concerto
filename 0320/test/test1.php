<?php

//use ArrayObject;
//use CallbackFilterIterator;


class TokenCollection extends ArrayObject
{
    /**
    *   __construct
    *
    *   @param string $source_code
    */
    public function getClasses()
    {
        
        
        
        $class = new CallbackFilterIterator(
            $this,
            function($current, $key, $iterator) {
                //return $current->is([T_CLASS, T_INTERFACE, T_TRAIT]);
                return $current;
            }
        );
        
        
        
        return $class;
        
        
        
    }
}


$obj = new TokenCollection([
    1,2,3,4,5,6,7,8,9
    
    
    
]);


foreach($obj as $x) {
    
    var_dump($x);
    echo "\n";
    
    
}





