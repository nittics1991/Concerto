<?php

declare(strict_types=1);

namespace Concerto\test\cache;

use Concerto\test\ConcertoTestCase;
use Concerto\cache\MemcacheCache;

class MemcacheCacheTest extends ConcertoTestCase
{
    /**
    *   @test
    *   @runInSeparateProcess
    **/
    public function basicSuccess()
    {
        $this->markTestIncomplete();
        
        //memcache not work in phpunit
        $memcache = new \Memcache();
        $memcache->addServer('localhost', 11211);
        
        $data = 'this is test';
        $memcache->set('ZZZZZZZZZZZZZZZZ', $data, 0, 1000);
    }
}
