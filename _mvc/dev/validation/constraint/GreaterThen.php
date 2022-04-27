<?php

/**
*   GreaterThen
*
*   @ver 180613
*/

declare(strict_types=1);

namespace dev\validation\constraint;

use dev\validation\AbstractConstraint;

class GreaterThen extends AbstractConstraint
{
    /**
    *   {inherit}
    *
    */
    public function isValid($val)
    {
        return $val > $this->parameters[0];
    }
}
