<?php

/**
*   ArrayAccessTrait
*
*   @version 190515
*/

declare(strict_types=1);

namespace Concerto\accessor;

trait ArrayAccessTrait
{
    /**
    *    {inherit}
    *
    **/
    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }
    
    /**
    *    {inherit}
    *
    **/
    public function offsetGet($offset)
    {
        return $this->$offset;
    }
    
    /**
    *    {inherit}
    *
    **/
    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
    }
    
    /**
    *    {inherit}
    *
    **/
    public function offsetUnset($offset): void
    {
        unset($this->$offset);
    }
}
