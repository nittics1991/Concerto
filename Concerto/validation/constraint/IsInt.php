<?php

/**
*   IsInt
*
*   @ver 180613
**/

declare(strict_types=1);

namespace Concerto\validation\constraint;

use Concerto\validation\AbstractConstraint;

class IsInt extends AbstractConstraint
{
    /**
    *   {inherit}
    *
    **/
    public function isValid($val)
    {
        return is_int($val);
    }
}
