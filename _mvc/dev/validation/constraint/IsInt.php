<?php

/**
*   IsInt
*
*   @ver 180613
*/

declare(strict_types=1);

namespace dev\validation\constraint;

use dev\validation\AbstractConstraint;

class IsInt extends AbstractConstraint
{
    /**
    *   @inheritDoc
    *
    */
    public function isValid($val)
    {
        return is_int($val);
    }
}
