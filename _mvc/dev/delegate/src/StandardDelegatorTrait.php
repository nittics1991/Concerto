<?php

namespace concerto\delegator;

trait StandardDelegatorTrait
{
    /**
    *   delegatorNamespace
    *
    *   @return string
    */
    abstract protected static function delegatorNamespace():string;
    
    /**
    *   convertToOriginal
    *
    *   @param object $original
    *   @return object
    */
    abstract protected static function convertToOriginal(
        object $delegator
    ):object;
    
    /**
    *   convertToDelegator
    *
    *   @param object $original
    *   @return object
    */
    abstract protected static function convertToDelegator(
        object $original
    ):object;
    
    /**
    *   isOriginalObject
    *
    *   @param mixed $target
    *   @return bool
    */
    //protected static function isOriginalObject(mixed $target):bool
    protected static function isOriginalObject($target):bool
    {
        $originalNamespace = static::class;
        return $target instanceof $originalNamespace;
    }
    
    /**
    *   isDelegatorObject
    *
    *   @param mixed $target
    *   @return bool
    */
    //protected static function isDelegatorObject(mixed $target):bool
    protected static function isDelegatorObject($target):bool
    {
        $delegatorNamespace = static::delegatorNamespace();
        return $target instanceof $delegatorNamespace;
    }
    
    /**
    *   convertAllArgumentsUgingDelegator
    *
    *   @param array $arguments
    *   @return array
    */
    protected static function convertAllArgumentsUgingDelegator(
        array $arguments
    ):array {
        $delegated = [];
        foreach($arguments as $argument) {
            $delegated[] = static::isOriginalObject($argument)?
                static::convertToDelegator($argument):
                $argument;
        }
        
        return $delegated;
    }
    
    /**
    *   convertAndExecuteAllArgumentsAndResult
    *
    *   @param callable $callback
    *   @param array $arguments
    *   @return mixed
    */
    protected static function convertAndExecuteAllArgumentsAndResult(
        callable $callback,
        array $arguments
    //): mixed {
    ) {
        $converted = static::convertAllArgumentsUgingDelegator(
            $arguments,
        );
        
        $result = call_user_func_array(
            $callback,
            $converted,
        );
        
        return static::isOriginalObject($result)?
            static::convertToDelegator($result):
            $result;
    }
}
