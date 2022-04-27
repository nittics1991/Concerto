<?php

declare(strict_types=1);

namespace test\Concerto\container;

use test\Concerto\ConcertoTestCase;
use Concerto\container\ServiceContainer;
use Concerto\container\exception\NotFoundException;
use Concerto\container\ReflectionContainer;
use Concerto\container\ServiceProviderContainer;
use test\Concerto\container\TestClassHasDependencies;
use test\Concerto\container\TestClassNotHasConstructParameter;
use test\Concerto\container\TestClassInterface;

class ServiceContainerTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function setParameter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();

        //array parameter
        $container->bind('ar', range(1, 5));
        $this->assertEquals(range(1, 5), $container->get('ar'));
    }

    /**
    *   @test
    */
    public function setFunction()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();

        //call_user_funcと同じこと
        $container->bind('func', ['is_int', 10]);
        $this->assertEquals(true, $container->get('func'));

        //引数がいっぱい
        $container->bind('substr', ['substr', 'abcdefghijklmn', 4, 2]);
        $this->assertEquals('ef', $container->get('substr'));

        //引数なし==>対策済
        $container->bind('path', ['get_include_path']);
        $this->assertEquals(get_include_path(), $container->get('path'));

        //引数無しで第2引数がarrayではなくstring
        $container->bind('path2', 'get_include_path');
        $this->assertEquals(get_include_path(), $container->get('path2'));

        //引数無しなら第2引数省略可
        $container->bind('get_include_path');
        $this->assertEquals(get_include_path(), $container->get('get_include_path'));

        //clusure
        $container->bind('closure', function () {
            return get_include_path();
        });
        $this->assertEquals(get_include_path(), $container->get('closure'));

        //clusureの場合、引数はコンテナ
        $container->bind('closure2', function ($container) {
            return $container;
        });
        $this->assertEquals(true, $container->get('closure2') instanceof ServiceContainer);
    }

    /**
    *   @test
    */
    public function setObject()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();

        //毎回違うobject
        $container->bind('bind', \StdClass::class);
        $this->assertNotSame($container->get('bind'), $container->get('bind'));

        //シングルトン
        $container->share('share', \StdClass::class);
        $this->assertSame($container->get('share'), $container->get('share'));

        //constructerパラメータ無し
        $container->bind('notConstructer', \DateTime::class);
        $this->assertEquals(true, $container->get('notConstructer') instanceof \DateTime);

        $container->bind(\ArrayObject::class, \ArrayObject::class);
        $this->assertEquals(true, $container->get(\ArrayObject::class) instanceof \ArrayObject);
        $this->assertEquals(new \ArrayObject([]), $container->get(\ArrayObject::class));

        //constructerパラメータ有
        $now = '20170214';
        $container->bind('hasConstructer', [\DateTime::class, $now]);
        $this->assertEquals($now, $container->get('hasConstructer')->format('Ymd'));

        //名前はclass名でOK
        $container->bind(TestClassHasDependencies::class, [TestClassHasDependencies::class, new \StdClass()]);
        $resolved = $container->get(TestClassHasDependencies::class);
        $this->assertInstanceOf(TestClassHasDependencies::class, $resolved);
        $this->assertInstanceOf('stdClass', $resolved->argument);

        //引数なければclass名のみ
        $container->bind(TestClassNotHasConstructParameter::class);
        $this->assertInstanceOf(TestClassNotHasConstructParameter::class, $container->get(TestClassNotHasConstructParameter::class));

        //interfaceとclassをbind
        $container->bind(TestClassInterface::class, TestClassNotHasConstructParameter::class);
        $this->assertInstanceOf(TestClassNotHasConstructParameter::class, $container->get(TestClassInterface::class));
    }

    /**
    *   @test
    */
    public function replaceService()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();
        $container->bind('service', function () {
            return new \StdClass();
        });

        $this->assertEquals(true, $container->get('service') instanceof \StdClass);

        //over write
        $container->bind('service', function () {
            return new \ArrayObject([]);
        });
        $this->assertEquals(true, $container->get('service') instanceof \ArrayObject);

        //over write with extend
        $container->extend('service', function ($instance, $container) {
            return new \StdClass();
        });
        $this->assertEquals(true, $container->get('service') instanceof \StdClass);

        //インスタンスの変更
        $container->extend('service', function ($instance, $container) {
            $instance->prop = 'ADD PROPERY';
            return $instance;
        });
        $this->assertEquals('ADD PROPERY', $container->get('service')->prop);
    }

    /**
    *   @test
    */
    public function changeArgument()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //引数がscala
        $container = new ServiceContainer();

        $container->bind(
            \ArrayObject::class,
            [
                function ($arg) {
                    return new \ArrayObject(['prop' => $arg], \ArrayObject::ARRAY_AS_PROPS);
                },
                12
            ]
        );
        $obj = $container->get(\ArrayObject::class);
        $this->assertEquals(12, $obj->prop);

       //引数がarray ==> プログラム対応済み
        $container = new ServiceContainer();

        $container->bind(
            \ArrayObject::class,
            [
                function ($arg) {
                    return new \ArrayObject($arg, \ArrayObject::ARRAY_AS_PROPS);
                },
                ['prop' => 12]
            ]
        );

        $obj = $container->get(\ArrayObject::class);
        $this->assertEquals(12, $obj->prop);
    }

    /**
    *   @test
    */
    public function parameter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();

        //nomal bind
        $container->bind('bind', 'DateTime');
        $this->assertEquals(true, $container->get('bind') instanceof \DateTime);

        //wrap closure
        $container->bind('wrap', function () {
            return 'DateTime';
        });
        $this->assertEquals('DateTime', $container->get('wrap'));
    }

    /**
    *   @test
    */
    public function raw()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $container = new ServiceContainer();

        //string
        $container->raw('bind', 'DateTime');
        $this->assertEquals('DateTime', $container->get('bind'));

        //object
        $container->raw('bind', new \DateTime());
        $this->assertEquals(true, $container->get('bind') instanceof \DateTime);

        //callable
        $container->raw('bind', 'is_int');
        $this->assertEquals('is_int', $container->get('bind'));

        $cls = new \ArrayObject([1, 2, 3]);
        $container->raw('bind', [$cls, 'count']);
        $this->assertEquals([$cls, 'count'], $container->get('bind'));
    }

    /**
    *   @test
    */
    public function notAcceptClosure()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('required scala,array,resource,object');
        $container = new ServiceContainer();

        $container->raw('wrap', function () {
            return 'DateTime';
        });
    }
}
