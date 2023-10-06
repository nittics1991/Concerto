<?php

/**
*   validate
*
*   @version 230118
*/

declare(strict_types=1);

namespace Concerto\standard;

interface Validatable
{
    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid(): bool;
}
