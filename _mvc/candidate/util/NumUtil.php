<?php

/**
*   NumUtil
*
*   @version 230216
*/

declare(strict_types=1);

namespace candidate\util;

use DomainException;

class NumUtil
{
    /**
    *   動的除算
    *
    *   @param int|float|string $num1
    *   @param int|float|string $num2
    *   @param int|float|string $default
    *   @return int|float|string
    */
    public static function div(
        int|float|string $num1,
        int|float|string $num2,
        int|float|string $default = 0,
    ): int|float|string {
        if (!is_numeric($num1)) {
            throw new DomainException(
                "ivalid num1:{$num1}",
            );
        }

        if (!is_numeric($num2)) {
            throw new DomainException(
                "ivalid num2:{$num2}",
            );
        }

        if (!is_numeric($default)) {
            throw new DomainException(
                "ivalid default:{$default}",
            );
        }

        return $num2 == 0 ?
            $default :
            $num1 / $num2;
    }
}
