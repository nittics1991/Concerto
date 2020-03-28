<?php

/**
*   aaaa
*
*   @version 190515
*/

declare(strict_types=1);

namespace Concerto\accessor;

use InvalidArgumentException;
use ReflectionClass;

class AAA
{
    /*
    protected bool $bool = false;
    protected int $int = 0;
    protected float $float = 0.0;
    protected string $string = '';
    protected array $array = [];
    protected object $object;
    protected iterable $iterable = [];
    protected self $self;
    protected MyClass $myClass;
    protected ?bool $nullable = null;
    
    
    
    */
    
    ///////////////////////////////////////////////////////////
    //  immutable
    ///////////////////////////////////////////////////////////
    
    /*
    * 
    */
    public function __isset(string $name)
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->hasProperty($name);
    }
    
    /*
    * 
    */
    public function __get(string $name)
    {
        $reflection = new ReflectionClass(static::class);
        if ($reflection->hasProperty($name)) {
            return $this->$name;
        }
        throw new InvalidArgumentException(
            "not defined property:{$name}"
        );
    }
    
    /*
    * 
    * 
    * 
    */
    protected function fromArray(array $data)
    {
        $reflection = new ReflectionClass(static::class);
        
        foreach ($data as $name => $val) {
            if (!$reflection->hasProperty($name)) {
                throw new InvalidArgumentException(
                    "not defined property:{$name}"
                );
            }
            
            //mutator
            $mutator = "set" . MbString::toUpperCamel($name);
            if ($reflection->hasMethod($name)) {
                $this->$name($val);
                continue;
            }
            
            $this->$name = $val;
        }
        return $this;
    }
    
    
    
    ///////////////////////////////////////////////////////////
    //  mutator
    ///////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    
    
}
