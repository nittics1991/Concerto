<?php

declare(strict_types=1);

namespace Concerto\test\container;

class TestClassHasDependencies
{
    public function __construct(\stdClass $argument)
    {
        $this->argument = $argument;
    }
}
