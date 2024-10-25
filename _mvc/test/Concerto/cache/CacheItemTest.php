<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\CacheItem;
use DateTime;
use DateInterval;
use DateTimeImmutable;

class CacheItemTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    /**
    *   基本処理確認
    *
    */
    #[Test]
    public function basic()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $key    = 'id1';
        $value  = 'STRING1';
        $expiry     = 3600;
        $isHit  = true;

        $object = new CacheItem($key, $value, $expiry, $isHit);
        $this->assertEquals($key, $object->getKey());
        $this->assertEquals($value, $object->get());
        $this->assertEquals($expiry, $object->getExpiry());
        $this->assertEquals($isHit, $object->isHit());

        $value = [1,2,3];
        $object->set($value);
        $this->assertEquals($value, $object->get());

        $expiry = new DateTimeImmutable("2016-11-24 12:34:56");
        $expect = (int)$expiry->format('U') - time();
        $object->expiresAt($expiry);
        $this->assertEquals($expect, $object->getExpiry());

        /*
        //$object->expiresAt(?\DateTimeInterface $expiration) が通らない 210511
        $interval = $this->getPrivateProperty($object, 'defaultlifetime');
        $expect = $interval;
        $object->expiresAt();
        $this->assertEquals($expect, $object->getExpiry());
       */

        $interval = 100;
        $expect = $interval;
        $object->expiresAfter($interval);
        $this->assertEquals($expect, $object->getExpiry());

        $interval = new DateInterval('PT100S');
        $expect = 100;
        $object->expiresAfter($interval);
        $this->assertEquals($expect, $object->getExpiry());
    }
}
