<?php

/**
*   TokenCollection
*
*   @version xxxxx
*/

namespace DocBlockGenerator;

use ArrayObject;
use Iterator;

class TokenCollection extends ArrayObject
{
    /**
    *   __construct
    *
    *   @param string $source_code
    */
    public function __construct(string $source_code)
    {
        parent::__construct(
            PhpToken::tokenize($source);
        );
    }
    
    /**
    *   getClasses
    *
    *   @return Iterator PhpToken
    */
    public function getClasses():Iterator
    {
        
        
        
        
        
        $class = new CallbackFilterIterator(
            $this,
            function($current, $key, $iterator) {
                return $current->is([T_CLASS, T_INTERFACE, T_TRAIT]);
            }
        );
        
        
        //抽出したtokenの下のclassNameを取得する
        
        
        
        
        
        foreach($filterIterator as $phptoken) {
            yield $phptoken;
        }
    }
    
    
    
    
    
    
    
    
    
    
}
