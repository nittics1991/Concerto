<?php

declare(strict_types=1);

namespace Concerto\test\auth\rememberme;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\rememberme\RememberMe;
use Psr\SimpleCache\CacheInterface;
use Concerto\hashing\RandomNumberGenaratorInterface;
use Prophecy\Argument;

class RememberMeTest extends ConcertoTestCase
{
    protected function basicSuccessProvider()
    {
        $cache = $this->prophesize()
            ->willImplement('Psr\SimpleCache\CacheInterface');
        $cache->has(Argument::any())->willReturn(false);
        $cache->get(Argument::any())->willReturn(null);

        $cache->set(Argument::any(), Argument::any(), Argument::any())
            ->will(function ($argv) {
                $this->get(Argument::any())->willReturn($argv[1]);
                $this->has(Argument::any())->willReturn(!empty($argv[1]));
            });

        $randomGenerator = $this->prophesize(
            RandomNumberGenaratorInterface::class
        );
        $randomGenerator->generate()->willReturn('1234567890ABCDEF');

        return new RememberMe(
            $cache->reveal(),
            $randomGenerator->reveal(),
            60,
            'testnamespace'
        );
    }

    /**
    *   @test
    */
    public function basicSuccess()
    {
        //*   @runInSeparateProcess があると動かない(phpunit bug)
        $this->markTestIncomplete();

        //empty
        $obj = $this->basicSuccessProvider();
        $this->assertEquals(false, $obj->isRegistered());

        //set user
        $userId = 'user1';
        $obj->register($userId);
        $this->assertEquals(true, $obj->isRegistered());
        $this->assertEquals($userId, $obj->getId());

        $cache = $this->getPrivateProperty($obj, 'cache');
        $id = $this->callPrivateMethod($cache, 'get', ['1234567890ABCDEF']);
        $this->assertEquals($userId, $id);

        //set key name
        $keyName = 'dummy';
        $obj->setKeyName($keyName);
        $expect = $this->getPrivateProperty($obj, 'keyName');
        $this->assertEquals($keyName, $expect);
    }
}
