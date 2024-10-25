<?php

declare(strict_types=1);

namespace test\Concerto\auth\authcounter;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\authcounter\IllegalUserCache;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class IllegalUserCacheTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        $_SERVER['remote_addr'] = '192.168.99.100';
    }

    /**
    */
    #[Test]
    public function getId()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $obj = new IllegalUserCache(
            $cache->reveal()
        );

        $this->assertEquals(
            '192_168_99_100',
            $this->callPrivateMethod(
                $obj,
                'getId',
                [],
            ),
        );
    }

    /**
    */
    #[Test]
    public function has()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->has(
            Argument::any(),
        )->willReturn(false, true);

        $obj = new IllegalUserCache(
            $cache->reveal()
        );

        $this->assertFalse($obj->has());
        $this->assertTrue($obj->has());
    }

    /**
    */
    #[Test]
    public function set()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface',
        );

        $cache->has(
            Argument::any(),
        )->willReturn(
            false,
            false,
            true,
        );

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(
            true,
        );

        $obj = new IllegalUserCache(
            $cache->reveal()
        );

        for ($i = 1; $i < 3; $i++) {
            $obj->set();
        }

        $cache->has(
            Argument::any(),
        )->shouldHaveBeenCalledTimes(
            2,
        );

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->shouldHaveBeenCalledTimes(
            2,
        );
    }
}
