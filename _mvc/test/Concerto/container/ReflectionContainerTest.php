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

class ReflectionContainerTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function reflectClassAndConstructorClassParameter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //no reflection
        $container = new ServiceContainer();
        $this->assertEquals(false, $container->has(TestClassHasDependencies::class));

        //reflectionで自動bind
        //same:new TestClassHasDependencies(StdClass)
        //引数もclassなら自動解決
        $container->delegate(new ReflectionContainer());

        $this->assertEquals(true, $container->has(TestClassHasDependencies::class));
        $this->assertEquals(
            true,
            $container->get(TestClassHasDependencies::class)->argument instanceof \StdClass
        );
    }

    /**
    */
    #[Test]
    public function failureReflectClassNotDefaultValue()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //no reflection
        $container = new ServiceContainer();
        $this->assertEquals(false, $container->has(\DateTimeImmutable::class));

        $container->delegate(new ReflectionContainer());
        $this->assertEquals(true, $container->has(\DateTimeImmutable::class));

        //default valueがclassでないから解決できない
        try {
            $obj = null;
            $obj = $container->get(\DateTimeImmutable::class);
        } catch (\Exception $e) {
            $this->assertEquals(null, $obj);
        }
    }
}
