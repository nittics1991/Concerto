<?php
declare(type_stricts=1);

namespace Concerto\accessor\reflectable;

use ReflectionClass;
use ReflectionProperty;
use Concerto\accessor\reflectable\ReflectePropertyTraitInterface;

trait ReflectePropertyTrait implements ReflectePropertyTraitInterface
{
    /**
    *   properties
    *
    *   @var ReflectionProperty[] [name=>val, ...]
    */
    protected array $properties = [];
    
    //classで定義されたpropertyを解析する
    //public string $fullName;     set/get OK property
    //protected string $fullName;  get only property
    //private string $fullName;    only this class used
    //protected array $users;      case <input name="users[first_name]">
    
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
}
