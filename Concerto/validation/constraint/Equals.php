<?php

/**
*   Equal
*
*   @ver 180613
*/

declare(strict_types=1);

namespace Concerto\validation\constraint;

use Concerto\validation\AbstractConstraint;

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
