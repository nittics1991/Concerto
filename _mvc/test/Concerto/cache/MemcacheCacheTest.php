<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\MemcacheCache;

class MemcacheCacheTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        if (!in_array('memcache', get_loaded_extensions())) {
            $this->markTestSkipped('memcache is not loaded');
            return;
        }
    }

    /**
    */
    #[Test]
    public function basicSuccess()
    {
        $this->markTestIncomplete('--- markTestIncomplete ---');

        //memcache not work in phpunit
        $memcache = new \Memcache();
        $memcache->addServer('localhost', 11211);

        $data = 'this is test';
        $memcache->set('ZZZZZZZZZZZZZZZZ', $data, 0, 1000);
    }
}
