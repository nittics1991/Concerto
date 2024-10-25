<?php

declare(strict_types=1);

namespace test\Concerto\container;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\container\ServiceContainer;
use Concerto\container\exception\NotFoundException;
use Concerto\container\ReflectionContainer;
use Concerto\container\ServiceProviderContainer;
use test\Concerto\container\TestClassHasDependencies;
use test\Concerto\container\TestClassMixReflectionAndProvider;
use test\Concerto\container\TestServiceProvider41;

class ServiceProviderTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function concatenateMultipleBindInServiceProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();
        $serviceProvider = new ServiceProviderContainer();
        $container->delegate($serviceProvider);

        $container->addServiceProvider(TestServiceProvider2::class);
        $this->assertEquals(true, ($pdo = $container->get(\PDO::class)) instanceof \PDO);

        $sql = "SELECT sqlite_version()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $actual = $stmt->fetch();
        $this->assertNotEquals(null, $actual[0]);
    }

    /**
    */
    #[Test]
    public function bootOfServiceProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();
        $serviceProvider = new ServiceProviderContainer();
        $container->delegate($serviceProvider);

        $container->addServiceProvider(TestServiceProvider31::class);
        $container->addServiceProvider(TestServiceProvider32::class);
        $container->bootServiceProviders();
        $this->assertEquals(true, ($pdo = $container->get(\PDO::class)) instanceof \PDO);

        $sql = "SELECT sqlite_version()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $actual = $stmt->fetch();
        $this->assertNotEquals(null, $actual[0]);
    }

    /**
    */
    #[Test]
    public function mixReflectionAndProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //only reflection
        $container = new ServiceContainer();
        $container->delegate(new ReflectionContainer());
        $this->assertEquals(
            true,
            $container->get(TestClassMixReflectionAndProvider::class)
                instanceof TestClassMixReflectionAndProvider
        );

        //only provider
        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(TestServiceProvider41::class);
        $this->assertEquals(
            true,
            $container->get(TestClassMixReflectionAndProvider::class)
                instanceof TestClassMixReflectionAndProviderOther
        );

        //reflection and provider
        //overwite provider, but not call
        $container = new ServiceContainer();
        $container->delegate(new ReflectionContainer());
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(TestServiceProvider41::class);

        $this->assertEquals(
            false,
            $container->get(TestClassMixReflectionAndProvider::class)
                instanceof TestClassMixReflectionAndProviderOther
        );

        //reflection and provider
        //extend provider by boot, but not call
        $container = new ServiceContainer();
        $container->delegate(new ReflectionContainer());
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(TestServiceProvider41::class);

        $this->assertEquals(
            false,
            $container->get(TestClassMixReflectionAndProvider::class)
                instanceof TestClassMixReflectionAndProviderOther
        );
    }
}
