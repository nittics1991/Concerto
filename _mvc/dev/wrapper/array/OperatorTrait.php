<?php

/**
*   OperatorTrait
*
*   @version 210723
*/

declare(strict_types=1);

namespace Concerto\wrapper\extend;

trait OperatorTrait
{
    /**
    *  append
    *
    *   @param StandardArrayObject $sao
    *   @return static
    */
    public function append(
    ): static {
      return new static(
        $this->toArray() +
        $sao->toArray()
      );
    }

    /**
    *  equals
    *
    *   @param StandardArrayObject $sao
    *   @return bool
    */
    public function equals(
    ): bool {
      return $this->toArray() === $sao->toArray();
    }

    /**
    *  notEquals
    *
    *   @param StandardArrayObject $sao
    *   @return bool
    */
    public function notEquals(
    ): bool {
      return !($this->equals($sao));
    }

    /**
    *   same 
    *
    *   @param StandardArrayObject $sao
    *   @return bool
    */
    public function same(
    ): bool {
      return $this->toArray() == $sao->toArray();
    }

    /**
    *  notSame
    *
    *   @param StandardArrayObject $sao
    *   @return bool
    */
    public function notSame(
    ): bool {
      return !($this->same($sao));
    }
}
