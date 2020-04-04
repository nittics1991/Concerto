<?php










////////////////////////////////////////////////////////////////////////

public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

////////////////////////////////////////////////////////////////////////
//type hint propertyで　できないか?
//ArrayDotでarrayからDotNotationName生成が必要


declare(type_stricts=1);

namespace Concerto\standard;



class Post
{
    //reflactionから自動生成する
    //public/protectedで定義されたpropertyを読み取り、propertiesとする
    //$name=>ReflectionPropertyの形
    //$nameはArrayDot形式
    
    protected array $properties = [];
    
    //以下 child class で作る
    //public int $fullName;     set/get OK property
    //protected int $fullName;  get only property
    //private int $fullName;    not data property. only this class used
    
    public function __construct(array $data)
    {
        $this->dfinedProperty()
        
        
        $this->fromArray($data);
    }
    
    //traitに移動?
    protected function dfinedProperty(array $data):Post
    {
        $reflectionClass = new ReflectionClass($this);
        $this->properties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PUBLIC
        );
    }
    
    
    //traitに移動?
    //再帰的な処理にしたい
    protected function fromArray(Iterable $data):Post
    {
        $array = is_array($data)?
            $data:iterator_to_array($data)
        
        array_walk_recursive(
            $array
            function (&$val, &$key) => {
                
                
            }
            return 
        );
        
        
        
        
        
        foreach ($data as $key => $val) {
            
            
            
            
            
            if (!in_array($key, $this->properties)) {
                throw new InvalidArgumentException(
                    "not defined key:{$key}"
                );
            }
            
            $this->container[$key] = $val;
            
        }
        
        return $this;
    }
    
    
    
    
    
}

