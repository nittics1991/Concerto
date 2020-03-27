<?php

declare(strict_types=1);

namespace Concerto\test\container;

use Concerto\container\provider\AbstractServiceProvider;
use Concerto\test\container\TestClassMixReflectionAndProviderOther;

class TestServiceProvider41 extends AbstractServiceProvider
{
    protected $provides = [
        TestClassMixReflectionAndProvider::class,
    ];

    public function register()
    {
        $this->bind(TestClassMixReflectionAndProvider::class, function () {
            return new TestClassMixReflectionAndProviderOther();
        });
    }
}
