<?php

declare(strict_types=1);

namespace test\Concerto\container;

use Concerto\container\provider\AbstractServiceProvider;

class TestServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
      \stdClass::class
    ];

    public function register()
    {
        $this->bind(\stdClass::class, function () {
            return new \stdClass();
        });
    }
}
