<?php

declare(strict_types=1);

namespace Concerto\test\cache;

use Concerto\test\ConcertoTestCase;
use Concerto\cache\SimpleCacheTrait;
use Concerto\cache\InvalidArgumentException;

class TestSimpleCacheTrait1
{
    use SimpleCacheTrait;
}

class SimpleCacheTraitTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function defaultLifeTimeSuccess()
    {
//      $this->markTestIncomplete();

        $obj = new TestSimpleCacheTrait1();
        $obj->setDefaultLifeTime(20);
        $expect = $this->getPrivateProperty($obj, 'defaultLifeTime');
        $this->assertEquals(20, $expect);
    }

    /**
    *   @test
    */
    public function parseExpireSuccess()
    {
//      $this->markTestIncomplete();

        $obj = new TestSimpleCacheTrait1();
        $obj->setDefaultLifeTime(20);
        //int
        $expect = $this->callPrivateMethod($obj, 'parseExpire', [999]);
        $this->assertEquals($expect, 999);
        //DateInterval
        $ttl = new \DateInterval("P1D");
        $expect = $this->callPrivateMethod($obj, 'parseExpire', [$ttl]);
        $this->assertEquals($expect, 60 * 60 * 24);
        //null
        $expect = $this->callPrivateMethod($obj, 'parseExpire', [null]);
        $this->assertEquals($expect, 20);
    }

    /**
    *   @test
    */
    public function parseExpireException()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ttl must be type int|DateInterval|null');

        $obj = new TestSimpleCacheTrait1();
        $expect = $this->callPrivateMethod($obj, 'parseExpire', ['123']);
    }

    /**
    *   @test
    */
    public function validateIterableException()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('key must be type iterable');

        $obj = new TestSimpleCacheTrait1();
        $expect = $this->callPrivateMethod($obj, 'validateIterable', ['DUMMY']);
    }

    /**
    *   @test
    */
    public function validateKeyException()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('key must be type string:11');

        $obj = new TestSimpleCacheTrait1();
        $expect = $this->callPrivateMethod($obj, 'validateKey', [11]);
    }
}
