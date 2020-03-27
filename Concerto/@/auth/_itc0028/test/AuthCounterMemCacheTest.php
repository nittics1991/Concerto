<?php

namespace Concerto\test\auth;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\AuthCounterMemCache;

class AuthCounterMemCacheTest extends ConcertoTestCase
{
    private $class;
    
    protected function setUp(): void
    {
    }
    
    /**
    *   @test
    **/
    public function connect()
    {
        
//      $this->markTestIncomplete();
        
        $object = new AuthCounterMemCache('ID1');
        
        $cache = $this->getPrivateProperty($object, 'cache');
        $this->assertEquals(true, $cache instanceof \Memcache);
        $status = $cache->getExtendedStats();
        $hosts = array_keys($status);
        $this->assertEquals('127.0.0.1:11211', $hosts[0]);
    }
    
    public function createKeyProvider()
    {
        return [
            ['asd.zxc', 'asd_zxc'],
            ['127.0.0.1', '127_0_0_1'],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider createKeyProvider
    **/
    public function createKey($data, $expect)
    {
        
//      $this->markTestIncomplete();
        
        $object = new AuthCounterMemCache('ID1');
        $this->assertEquals($expect, $this->callPrivateMethod($object, 'createKey', [$data]));
    }
}
