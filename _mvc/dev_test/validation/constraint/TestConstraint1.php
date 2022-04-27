<?php

declare(strict_types=1);

namespace test\Concerto\validation\constraint;

use dev\validation\AbstractConstraint;

class TestConstraint1 extends AbstractConstraint
{
    public function isValid($val)
    {
        return $val > $this->parameters[0];
    }
}
