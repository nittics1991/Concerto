<?php

declare(strict_types=1);

namespace Concerto\test\validation\constraint;

use Concerto\validation\AbstractConstraint;

class TestConstraint1 extends AbstractConstraint
{
    public function isValid($val)
    {
        return $val > $this->parameters[0];
    }
}
