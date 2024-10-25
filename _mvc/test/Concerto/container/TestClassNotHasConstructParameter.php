<?php

declare(strict_types=1);

namespace test\Concerto\container;

use test\Concerto\container\TestClassInterface;

class TestClassNotHasConstructParameter implements TestClassInterface
{
    public $argument;

    public function __construct()
    {
        $this->argument = TestClassNotHasConstructParameter::class;
    }

    /**
    *   @inheritDoc
    */
    public function get()
    {
        return TestClassNotHasConstructParameter::class . ' _get()';
    }
}
