<?php

declare(strict_types=1);

namespace test\Concerto\container;

use Concerto\container\provider\AbstractServiceProvider;
use test\Concerto\container\TestClassMixReflectionAndProviderOther;

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
