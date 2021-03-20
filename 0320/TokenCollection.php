<?php

/**
*   TokenCollection
*
*   @version xxxxx
*/

namespace DocBlockGenerator;

use ArrayIterator;
use Iterator;

class TokenCollection extends ArrayObject
{
    /**
    *   __construct
    *
    *   @param xxxx
    */
    public function __construct(string $source)
    {
        parent::__construct(
            PhpToken::tokenize($source);
        );
    }
    
    /**
    *   getClasses
    *
    *   @return array
    */
    public function getClasses():Iterator
    {
        
        $filterIterator = new class() extends FilterIterator {
           public function accept() {
               $current = parent::current();
               
               return  $current === T_CLASS
                || $current === T_INTERFACE
                || $current === T_TRAIT;
            }
        };
        
        $this->setIteratorClass(ArrayIterator::class);
        
        foreach(new $filterIterator(this) as $phptoken) {
            yield new //phpTokenをwrapしたclass
            
            
            
            
            
            
            
        }
    }
    
    
    
    
}
