<?php

//Post要確認

/**
*   Query
*
*   @version 200516
*/

declare(type_stricts=1);

namespace Concerto\standard;

//use ArrayAccess;
use Concerto\accessor\{
    //ArrayAccessTrait,
    InitializePropertyTrait,
    ReflectePropertyTrait,
    ReflectePropertyTraitInterface
};

//ArrayAccess必要か?
class Query implements //ArrayAccess,
    ReflectePropertyTraitInterface
{
    //use ArrayAccessTrait;
    use InitializePropertyTrait;
    use ReflectePropertyTrait;
    
    /**
    *   __construct
    *
    *   @param array $data
    *   @param array $init
    */
    public function __construct(array $data, array $init = [])
    {
        //$this->fromArray($data);
        
        //Psr7Request->getBody()でQueryパラメータのみ取り込む
        //初期値をimport後、requestをimport(over write)
        //最初にthis->propertiesでclass定義時に初期値を決める事も
        //$this->importExceptUndefinedProperties($this->properties);
        //$this->importExceptUndefinedProperties($init);
        //$this->importExceptUndefinedProperties($data);
        
        $this->initializeProperties($data, $init);
    }
    
    
    //ReflectePropertyTraitに持っていくか?
    //別途traitとするか?(こっちのほうが良さそう)
    /**
    *   未定義のpropertyデータを無視してfromArray
    *
    *   @param $data
    *   @return $this 
    */
    //public function importExceptUndefinedProperties(array $data)
    //{
        //return $this->fromArray(
            //array_intersect_key(
                //$data,
                //$this->properties
            //)
        //);
    //}
}
