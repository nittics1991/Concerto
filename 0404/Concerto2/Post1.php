<?php


////////////////////////////////////////////////////////////////////////
//この場合配列型のHTML要素の場合,難しい
//シンプルな形にしたい
//標準機能で作り　traitで機能追加する形にしたい
//accessorは凝りすぎ

declare(type_stricts=1);

namespace Concerto\standard;

use Concerto\accessor\ArrayAccessTrait;
use Concerto\accessor\AttributeInterface;
use Concerto\accessor\impl\AttributeImplTrait;

class Post implements AttributeInterface
{
    use AttributeImplTrait;
    use ArrayAccessTrait;
    
    use ValidatorTrait;
    
    public function __construct(array $data)
    {
        $this->fromArray($data);
    }
    
    protected function fromArray(array $data):Post
    {
        foreach ($data as $key => $val) {
            //配列型のHTML要素の場合どうする?
            
            
            
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

