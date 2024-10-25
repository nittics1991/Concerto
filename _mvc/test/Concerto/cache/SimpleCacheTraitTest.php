<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\SimpleCacheTrait;
use Concerto\cache\InvalidArgumentException;

class TestSimpleCacheTrait1
{
    use SimpleCacheTrait;
}

class SimpleCacheTraitTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function defaultLifeTimeSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSimpleCacheTrait1();
        $obj->setDefaultLifeTime(20);
        $expect = $this->getPrivateProperty($obj, 'defaultLifeTime');
        $this->assertEquals(20, $expect);
    }

    /**
    */
    #[Test]
    public function parseExpireSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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
}
