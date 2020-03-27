<?php

declare(strict_types=1);

namespace Concerto\test\cache;

use Concerto\test\ConcertoTestCase;
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
    *   @test
    **/
    public function basic()
    {
//      $this->markTestIncomplete();
        
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
        
        $interval = $this->getPrivateProperty($object, 'defaultlifetime');
        $expect = $interval;
        $object->expiresAt();
        $this->assertEquals($expect, $object->getExpiry());
        
        $interval = 100;
        $expect = $interval;
        $object->expiresAfter($interval);
        $this->assertEquals($expect, $object->getExpiry());
        
        $interval = new DateInterval('PT100S');
        $expect = 100;
        $object->expiresAfter($interval);
        $this->assertEquals($expect, $object->getExpiry());
    }
    
    /**
    *
    **/
    public function ExceptionConstructProvider()
    {
        return [
            [12, 'STRING', 100, false],
            ['id', 'STRING', '100', false],
            ['id', 'STRING', 100, 'false']
        ];
    }
    
    /**
    * @test
    */
    public function ExceptionExpiresAt()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('expiration must be DateTimeInterface');
        $key    = 'id1';
        $value  = 'STRING1';
        $expiry     = 3600;
        $isHit  = true;
        $object = new CacheItem($key, $value, $expiry, $isHit);
        $object->expiresAt("STRING");
    }
    
    /**
    * @test
    */
    public function ExceptionExpiresAfter()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('expiration must be DateInterval or integer');
        $key    = 'id1';
        $value  = 'STRING1';
        $expiry     = 3600;
        $isHit  = true;
        $object = new CacheItem($key, $value, $expiry, $isHit);
        $object->expiresAfter("STRING");
    }
}
