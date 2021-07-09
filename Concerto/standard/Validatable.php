<?php

/**
*   validate
*
*   @version 150731
*/

declare(strict_types=1);

namespace Concerto\standard;

interface Validatable
{
    /**
    *     判定
    *
    */
    public function isValid();
}
