<?php

declare(strict_types=1);

namespace test\Concerto\validation\constraint;

use dev\validation\AbstractConstraint;

class TestConstraint2 extends AbstractConstraint
{
    public function isValid($val)
    {
        $this->value = $val;
        return $val < $this->parameters[1];
    }

    public function name()
    {
        return 'OverWriteNameMethod';
    }

    public function message()
    {
        return sprintf(
            'OverWriteNameMethod value=%d param=%d',
            $this->value,
            isset($this->parameters[1]) ? $this->parameters[1] : ''
        );
    }
}
