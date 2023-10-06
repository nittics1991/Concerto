<?php

declare(strict_types=1);

namespace test\Concerto\validation\constraint;

use dev\validation\AbstractConstraint;

class TestConstraint3 extends AbstractConstraint
{
    protected $message = ':attribute:overWriteMessage';

    public function isValid($val)
    {
        return is_int($val);
    }
}
