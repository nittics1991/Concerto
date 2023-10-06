<?php

/**
*   ValueReturnMethodTrait
*
*   @version 210727
*/

declare(strict_types=1);

namespace candidate\wrapper\array\extend;

trait ValueReturnMethodTrait
{
    /**
    *   nth
    *
    *   @param int $position
    *   @return mixed
    */
    public function nth(
        int $position
    ): mixed {
        $array = $this->toArray();
        $counter = 0;

        if ($position < 0) {
            $array = array_reverse($array, true);
            $position = -1 * $position;
            $counter = 1;
        }

        while ($counter < $position) {
            next($array);
            $counter++;
        }
        return current($array);
    }

    /**
    *   first
    *
    *   @return mixed
    */
    public function first(): mixed
    {
        return static::nth(0);
    }

    /**
    *   last
    *
    *   @return mixed
    */
    public function last(): mixed
    {
        return static::nth(-1);
    }

    /**
    *  max
    *
    *   @param ?int $flags
    *   @return mixed
    */
    public function max(
        ?int $flags = SORT_REGULAR
    ): mixed {
        $array = $this->toArray();
        rsort($array, $flags);
        return current($array);
    }

    /**
    *  min
    *
    *   @param ?int $flags
    *   @return mixed
    */
    public function min(
        ?int $flags = SORT_REGULAR
    ): mixed {
        $array = $this->toArray();
        sort($array, $flags);
        return current($array);
    }
}
