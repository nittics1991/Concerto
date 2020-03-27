<?php

declare(strict_types=1);

namespace Concerto\test\auth\authcounter;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authcounter\IllegalUserCache;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class IllegalUserCacheTest extends ConcertoTestCase
{
    protected function basicSuccessProvider()
    {
        $cache = $this->prophesize()
            ->willImplement('Psr\SimpleCache\CacheInterface');
        $cache->has(Argument::any())->willReturn(false);
        $cache->get(Argument::any())->willReturn(null);
        
        $cache->set(Argument::any(), Argument::any(), Argument::any())
            ->will(function ($argv) {
                $this->get(Argument::any())->willReturn($argv[1]);
                $this->has(Argument::any())->willReturn(!empty($argv[1]));
            });
        
        return new IllegalUserCache(
            $cache->reveal()
        );
    }
    
    /**
    *   @test
    *   @runInSeparateProcess
    **/
    public function basicSuccess()
    {
        // $this->markTestIncomplete();
        
        $_SERVER['remote_addr'] = '192.168.0.12';
        
        //empty
        $obj = $this->basicSuccessProvider();
        $this->assertEquals(false, $obj->has());
        
        $id = $this->callPrivateMethod($obj, 'getId', []);
        $this->assertEquals('192_168_0_12', $id);
        
        $obj->set();
        $this->assertEquals(true, $obj->has());
        
        $cache = $this->getPrivateProperty($obj, 'cache');
        $this->assertEquals(true, $cache->has($id));
    }
}
