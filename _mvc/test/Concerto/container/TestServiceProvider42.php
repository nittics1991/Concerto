<?php

declare(strict_types=1);

namespace test\Concerto\container;

use Concerto\container\provider\AbstractServiceProvider;
use test\Concerto\container\TestClassMixReflectionAndProviderOther;
use Concerto\container\provider\BootableServiceProviderInterface;

class TestServiceProvider42 extends AbstractServiceProvider implements
    BootableServiceProviderInterface
{
    protected $provides = [
        TestClassMixReflectionAndProvider::class,
    ];

    public function register()
    {
    }

    public function boot()
    {
        $this->extend(TestClassMixReflectionAndProvider::class, function () {
            return new TestClassMixReflectionAndProviderOther();
        });
    }
}
