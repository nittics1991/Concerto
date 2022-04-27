<?php

declare(strict_types=1);

namespace test\Concerto\auth\authcounter;

use test\Concerto\ConcertoTestCase;
use Concerto\auth\authcounter\AuthCounter;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class AuthCounterTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    /**
    *   @test
    */
    public function increment()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->willReturn(0);

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(
            true,
        );

        $obj = new AuthCounter(
            $cache->reveal()
        );

        $increment_max_count = 10;

        for ($i = 1; $i <= $increment_max_count; $i++) {
            $obj->increment();
        }

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalledTimes(
            $increment_max_count
        );
        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalledTimes(
            $increment_max_count
        );
    }

    /**
    *   @test
    */
    public function reached()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->willReturn(0, 1);

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(
            true,
        );

        $expect_count = 10;

        $obj = new AuthCounter(
            $cache->reveal(),
            $expect_count,
        );

        $this->assertEquals(
            $expect_count,
            $this->getPrivateProperty($obj, 'limit'),
        );

        $this->assertFalse($obj->reached());

        $this->setPrivateProperty($obj, 'limit', 1);

        $this->assertTrue($obj->reached());

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalledTimes(
            2,
        );
    }

    /**
    *   @test
    */
    public function clear()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(
            true,
        );

        $obj = new AuthCounter(
            $cache->reveal(),
        );

        $obj->clear();

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalledTimes(
            1,
        );
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

        $obj = new AuthCounter(
            $cache->reveal(),
        );

        $keyName = 'DUMMY';

        $obj->setKeyName($keyName);

        $this->assertEquals(
            $keyName,
            $this->getPrivateProperty(
                $obj,
                'keyName',
            ),
        );
    }
}
