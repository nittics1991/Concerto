<?php

namespace concerto\delegator;

use Concerto\delegator\StandardDelegatorTrait;


abstract class StandardDelegatorObject
{
    use StandardDelegatorTrait;
    
    /**
    *   @var string 
    */
    protected static string $delegatorNamespace;
    
    /**
    *   @var object 
    */
    protected object $delegator;
    
    /**
    *   {inherit}
    */
    protected static function delegatorNamespace():string
    {
        return static::$delegatorNamespace;
    }
    
    /**
    *   {inherit}
    */
    abstract protected static function convertToOriginal(
        object $delegator
    ):object;
    
    /**
    *   {inherit}
    */
    abstract protected static function convertToDelegator(
        object $original
    ):object;
    
    /**
    *   {inherit}
    */
    public function __call(
        string $name,
        array $arguments
    //): mixed {
    ) {
        return static::convertAndExecuteAllArgumentsAndResult(
            [static::class, $name],
            $arguments
        );
    }
    
    /**
    *   {inherit}
    */
    public static function __callStatic(
        string $name,
        array $arguments
    //): mixed {
    ) {
        return static::convertAndExecuteAllArgumentsAndResult(
            [static::class, $name],
            $arguments
        );
    }
    
    /**
    *   {inherit}
    */
    public function __get(
        string $name
    //): mixed {
    ) {
        return $this->delegator->$name;
    }
    
    /**
    *   {inherit}
    */
    public function __set(
        string $name,
        array $arguments
    ): void {
        $this->delegator->$name = $arguments;
    }
    
    /**
    *   {inherit}
    */
    public function __isset(
        string $name
    ): bool {
        return isset($this->delegator->$name);
    }
    
    /**
    *   {inherit}
    */
    public function __unset(string $name):void {
        unset($this->delegator->$name);
    }
}
