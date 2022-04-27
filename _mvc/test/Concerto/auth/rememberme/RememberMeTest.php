<?php

declare(strict_types=1);

namespace test\Concerto\auth\RememberMe;

use test\Concerto\ConcertoTestCase;
use Concerto\auth\RememberMe\RememberMe;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class RememberMeTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    /**
    *   @test
    */
    public function setKeyName()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $generator = $this->prophesize(
            'Concerto\hashing\RandomNumberGenaratorInterface'
        );

        $obj = new RememberMe(
            $cache->reveal(),
            $generator->reveal(),
        );

        $current_key_name = $this->getPrivateProperty(
            $obj,
            'keyName',
        );

        $new_key_name = 'DUMMY';

        $obj->setKeyName($new_key_name);

        $this->assertEquals(
            $new_key_name,
            $this->getPrivateProperty(
                $obj,
                'keyName',
            ),
        );
    }

    /**
    *   @test
    */
    public function getId()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect_id = 'DUMMY';

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->willReturn($expect_id);

        $generator = $this->prophesize(
            'Concerto\hashing\RandomNumberGenaratorInterface'
        );

        $obj = new RememberMe(
            $cache->reveal(),
            $generator->reveal(),
        );

        $this->assertEquals(
            $expect_id,
            $obj->getId(),
        );

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalled();
    }

    /**
    *   @test
    */
    public function isRegistered()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->willReturn('DUMMY');

        $cache->has(
            Argument::any(),
        )->willReturn(false, true);

        $cacheStub = $cache->reveal();

        $generator = $this->prophesize(
            'Concerto\hashing\RandomNumberGenaratorInterface'
        );

        $obj = new RememberMe(
            $cacheStub,
            $generator->reveal(),
        );

        $cookie = $this->prophesize(
            'Concerto\cache\CookieCache'
        );

        $cookie->get(
            Argument::any(),
            Argument::any(),
        )->willReturn(null, 'REGISTER');

        $cookieStub = $cookie->reveal();

        $this->setPrivateProperty(
            $obj,
            'cookie',
            $cookieStub,
        );

        //cookie return null
        $this->assertFalse($obj->isRegistered());

        //cookie return STRING
        $this->assertFalse($obj->isRegistered());

        //cache registered
        $this->assertTrue($obj->isRegistered());
    }

    /**
    *   @test
    */
    public function register()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->has(
            Argument::any(),
        )->willReturn(false, true, false);

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(true);

        $cacheStub = $cache->reveal();

        $generator = $this->prophesize(
            'Concerto\hashing\RandomNumberGenaratorInterface'
        );

        $generator->generate()->willReturn('RANDOM');

        $obj = new RememberMe(
            $cacheStub,
            $generator->reveal(),
        );

        $cookie = $this->prophesize(
            'Concerto\cache\CookieCache'
        );

        $cookie->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(true);

        $cookieStub = $cookie->reveal();

        $this->setPrivateProperty(
            $obj,
            'cookie',
            $cookieStub,
        );

        //cache had generated number. retry register()
        $obj->register('USER1');

        //new generated number
        $obj->register('USER2');

        $cache->has(
            Argument::any(),
        )->shouldHaveBeenCalledTimes(3);

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalledTimes(2);
    }
}
