<?php

namespace Concerto\test\delegator;

use Concerto\delegator\StandardDelegatorObject;
use Concerto\test\delegator\{
    LibClass,
    MyInterface
};

class DelegatedClass extends StandardDelegatorObject implements MyInterface
{
    /**
    *   @var string 
    */
    protected static string $delegatorNamespace = LibClass::class;
    
    /**
    *   @var string //MyInterface
    */
    protected string $id;
    
    /**
    *   __construct
    *
    *   @param array $arguments
    *   @return object
    */
    public function __construct(
        string $id
    ) {
        $delegatorNamespace = static::delegatorNamespace();
        $this->delegator = new $delegatorNamespace(
            $id
        );
    }
    
    /**
    *   {inherit}
    */
    protected static function convertToOriginal(
        object $delegator
    ):object{
        return call_user_func_array(
            [
                static::class,
                '__construct',
            ],
            $delegator->getLibId(),
        );
    }
    
    /**
    *   {inherit}
    */
    protected static function convertToDelegator(
        object $original
    ):object{
        $delegatorNamespace = static::delegatorNamespace();
        
        return new $delegatorNamespace(
            $original->getMyId()
        );
    }
    
    
    //MyInterface method
    
    /**
    *   {inherit}
    */
    public function nonInjected(string $str)
    {
        return static::convertAndExecuteAllArgumentsAndResult(
            [
                $this->delegator,
                'nonInjected',
            ],
            [$str]
        );
    }
    
    /**
    *   {inherit}
    */
    public function injected(MyInterface $obj)
    {
        return static::convertAndExecuteAllArgumentsAndResult(
            [
                $this->delegator,
                'injected',
            ],
            [$obj]
        );
    }
    
    
    
    //convertToOriginalで使うconstruct引数取得用
    public function getMyId()
    {
        return $this->delegator->getLibId();
    }
    
    
    
    
    
    //bridgeオリジナルmethod
    public function bridge($str)
    {
        var_dump($str);
        return $str;
    }
}
