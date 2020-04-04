<?php










////////////////////////////////////////////////////////////////////////
//type hint propertyで　できないか?
//ArrayDotでarrayからDotNotationName生成が必要


declare(type_stricts=1);

namespace Concerto\standard;



class Post
{
    /**
    *   properties
    *
    *   @var ReflectionProperty[] [name=>val, ...]
    */
    protected array $properties = [];
    
    //以下 child class で作る
    //public string $fullName;     set/get OK property
    //protected string $fullName;  get only property
    //private string $fullName;    not data property. only this class used
    //protected array $users;  <input name="users[first_name]">
    
    public function __construct(array $data)
    {
        $this->reflecteProperty()
            ->fromArray($data);
    }
    
    //traitに移動?
    protected function reflecteProperty(array $data):static
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PUBLIC
        );
        
        $this->properties = array_map(
            function($property) {
                return [$property->getName() => $property];
            },
            $properties
        );
        
        return $this;
    }
    
    //__getは $this->properties の全て
    //__setはpublicのみ受付
    
    
    
    //traitに移動?
    protected function fromArray(array $data):static
    {
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
        
    public function toArray():array
    {
        return array_map(
            function($name) {
                return $this->$name;
            },
            array_keys($this->properties)
        );
    }
}

