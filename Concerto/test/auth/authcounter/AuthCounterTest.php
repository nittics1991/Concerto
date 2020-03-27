<?php

declare(strict_types=1);

namespace Concerto\test\auth\authcounter;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authcounter\AuthCounter;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class AuthCounterTest extends ConcertoTestCase
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
        $cache->clear(Argument::any())
            ->will(function ($argv) {
                $this->get(Argument::any())->willReturn(null);
                $this->has(Argument::any())->willReturn(false);
            });
        
        return new AuthCounter(
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
        
        //empty
        $obj = $this->basicSuccessProvider();
        $this->assertEquals(false, $obj->reached());
        
        //inclement
        $obj->increment();
        $cache = $this->getPrivateProperty($obj, 'cache');
        $this->assertEquals(1, $cache->get('failureCount'));
        $this->assertEquals(false, $obj->reached());
        
        //countup
        for ($i = 0; $i < 4; $i++) {
            $obj->increment();
        }
        $this->assertEquals(5, $cache->get('failureCount'));
        $this->assertEquals(true, $obj->reached());
        
        $obj->clear();
        $this->assertEquals(0, $cache->get('failureCount'));
        $this->assertEquals(false, $obj->reached());
        
        //set key name
        $keyName = 'dummy';
        $obj->setKeyName($keyName);
        $expect = $this->getPrivateProperty($obj, 'keyName');
        $this->assertEquals($keyName, $expect);
    }
}
