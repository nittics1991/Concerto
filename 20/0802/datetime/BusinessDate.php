<?php

/**
*   BusinessDate
*
*   @version 200802
*/

declare(strict_types=1);

namespace Concerto\datetime;

use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class BusinessDate
{
    
    //use FiscalYeay
    
    
    
    
    /**
    *   base_datetime_namespace
    *
    *   @var string
    */
    protected $base_datetime_namespace = DatetimeImmutable::class;
    
    /**
    *   setBaseDateTimeNamespace
    *
    *   @param string $name
    *   @return BusinessDate 
    */
    public function setBaseDateTimeNamespace(
        string $namespace,
    ) : {
        $this->base_datetime_namespace = $namespace;
        return $this;
    }
    
    /**
    *   callMethod
    *
    *   @param string $name
    *   @param array $arguments
    *   @return mixed 
    */
    protected static function callMethod(string $name, array $arguments)
    {
        $result = call_user_func_array(
            [$this->base_datetime_namespace, $name],
            $arguments
        );
        
        if ($result instanceof DateTimeInterface) {
            return $result;
        }
        
        $reflectionClass = new ReflectionClass(
            $this->base_datetime_namespace
        );
        
        return $reflectionClass->newInstance(
            $result->format(DateTimeInterface::ATOM),
            $result->getTimezone()
        );
    }
    
    /**
    *   {inherit}
    *
    */
    public function __call(string $name, array $arguments)
    {
        return BusinessDate::callMethod($name, $arguments);
    }
    
    /**
    *   {inherit}
    *
    */
    public static function __callStatic(string $name, array $arguments)
    {
        return BusinessDate::callMethod($name, $arguments);
    }
}
