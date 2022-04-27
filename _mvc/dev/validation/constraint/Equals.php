<?php

/**
*   Equal
*
*   @ver 180613
*/

declare(strict_types=1);

namespace dev\validation\constraint;

use dev\validation\AbstractConstraint;

class Equals extends AbstractConstraint
{
    /**
    *   {inherit}
    *
    */
    public function isValid($val)
    {
        return $val == $this->parameters[0];
    }
}
