<?php

declare(strict_types=1);

namespace test\Concerto\container;

class TestClassHasDependencies
{
    public $argument;

    public function __construct(\stdClass $argument)
    {
        $this->argument = $argument;
    }
}
