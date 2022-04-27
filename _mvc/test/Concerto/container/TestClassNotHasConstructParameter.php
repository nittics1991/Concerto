<?php

declare(strict_types=1);

namespace test\Concerto\container;

use test\Concerto\container\TestClassInterface;

class TestClassNotHasConstructParameter implements TestClassInterface
{
    public function __construct()
    {
        $this->argument = TestClassNotHasConstructParameter::class;
    }

    /**
    *   {inherit}
    */
    public function get()
    {
        return TestClassNotHasConstructParameter::class . ' _get()';
    }
}
