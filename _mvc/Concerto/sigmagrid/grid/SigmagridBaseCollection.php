<?php

/**
*   SigmagridBaseCollection
*
*   @version 221213
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use ArrayObject;
use Concerto\standard\Validatable;

/**
*   @template TValue
*   @extends ArrayObject<int|string, TValue>
*/
class SigmagridBaseCollection extends ArrayObject implements
    Validatable
{
    /**
    *   @var mixed[]
    */
    protected array $valid = [];

    /**
    *   @inheritDoc
    */
    public function isValid(): bool
    {
        $this->valid = [];
        $cnt = 0;

        $this->isValidCom();

        foreach ($this as $obj) {
            $result = $obj->isValid();
            if ($result !== true) {
                $this->valid[$cnt] = $obj->getValidError();
            }
            $cnt++;
        }
        return count($this->valid) === 0;
    }

    /*
    *   isValidCom(over write)
    *
    */
    public function isValidCom(): void
    {
    }

    /**
    *   getValidError
    *
    *   @return mixed[]
    */
    public function getValidError(): mixed
    {
        return $this->valid;
    }

    /**
    *   toArray
    *
    *   @return mixed[]
    */
    public function toArray(): mixed
    {
        $array = [];
        foreach ($this as $obj) {
            $array[] = $obj;
        }
        return $array;
    }
}
