<?php

/**
*   ReflectAttributeTrait
*
*   @version 200405
*/

declare(type_stricts=1);

namespace Concerto\accessor;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;

trait ReflectePropertyTrait
{
    /**
    *   properties
    *
    *   @var ReflectionProperty[] [name=>val, ...]
    */
    protected array $properties;
    
    /*
    *   classで定義されたpropertyを解析する
    *   public string $fullName;     set/get OK
    *   protected string $fullName;  get only
    *   private string $fullName;    private
    */
    
    /**
    *   classのpropertyを解析してpropertiesに定義
    *
    */
    private function reflecteProperty()
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PUBLIC
        );
        
        foreach($properties as $property) {
            if ($property->getName() == 'properties') {
                continue;
            }
            $this->properties[$property->getName() => $property];
        }
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __get(string $name)
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        if (!array_key_exists($name, $this->properties)) {
           throw new InvalidArgumentException(
                "not defined property:{$name}"
            );
        }
        return $this->$name;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __isset(string $name): bool
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        return array_key_exists($name, $this->properties);
    }
    
    /**
    *   fromArray
    *
    *   @param array $data
    */
    private function fromArray(array $data)
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        foreach($data as $name => $val) {
            if (!array_key_exists($name, $this->properties)) {
                throw new InvalidArgumentException(
                    "not defined property:{$name}"
                );
            }
            
            if (($this->properties[$name])->isPrivate()) {
                throw new InvalidArgumentException(
                    "invalid visibility:{$name}"
                );
            }
            $this->$name = $val;
        }
        return $this;
    }
        
    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray():array
    {
        if (!isset($this->properties)) {
            $this->reflecteProperty();
        }
        
        return array_map(
            function($name) {
                return $this->$name;
            },
            array_keys($this->properties)
        );
    }
}
