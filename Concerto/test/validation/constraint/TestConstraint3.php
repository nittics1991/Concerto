<?php

declare(strict_types=1);

namespace Concerto\test\validation\constraint;

use Concerto\validation\AbstractConstraint;

class TestConstraint3 extends AbstractConstraint
{
    protected $message = ':attribute:overWriteMessage';

    public function isValid($val)
    {
        return is_int($val);
    }
}
