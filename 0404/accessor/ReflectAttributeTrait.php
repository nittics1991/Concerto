<?php

/**
*   ReflectAttributeTrait
*
*   @version aaa
**/

declare(strict_types=1);

namespace Concerto\accessor\reflectable;

use InvalidArgumentException;
use Concerto\accessor\reflectable\ReflectePropertyTraitInterface;

trait ReflectAttributeTrait implements ReflectePropertyTraitInterface
{
    
    
    
    //AttributeTraitからコピー
    //結構凝ってる
    //簡単化できないか?
    
    
    
    
    /**
    *   {inherit}
    *
    **/
    public function __set(string $name, $value): void
    {
        $this->setDataToContainer($name, $value);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __get(string $name)
    {
        return $this->getDataFromContainer($name);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __isset(string $name): bool
    {
        $val = $this->getDataFromContainer($name);
        return isset($val);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __unset(string $name): void
    {
        $this->unsetDataFromContainer($name);
    }
}
