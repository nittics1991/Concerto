<?php

declare(strict_types=1);

namespace test\Concerto\reflection\tester;

use test\Concerto\reflection\tester\ReflectionDataTypeTester1;

class ReflectionDataTypeTester2 extends ReflectionDataTypeTester1
{
    public self $self;
    public parent $parent;
    public ReflectionDataTypeTester1 $tester1;

    public function retrunStatic(): static
    {
        return $this;
    }
}
