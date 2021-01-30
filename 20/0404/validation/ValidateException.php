<?php

/**
*   ValidateException
*
*   @version 200405
*/

declare(type_stricts=1);

namespace Concerto\validation;

use RuntimeException;
use Concerto\backtrace\Backtrace;

class ValidateException extends RuntimeException
{
    /**
    *   getValidatorInfo
    *
    *   @return Backtrace
    */
    public function getValidatorInfo():Backtrace
    {
        $traces = $this->getTrace();
        return new Backtrace($traces[0]);
    }
}
