<?php

declare(strict_types=1);

namespace Concerto\test\cache;

use Concerto\test\ConcertoTestCase;
use Concerto\cache\SessionCache;
use Concerto\cache\InvalidArgumentException;

class SessionCacheTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicSuccess()
    {
//      $this->markTestIncomplete();

        $obj = new SessionCache('test');

        $actual = [
            'i_prop' => 123,
            's_prop' => 'string',
            'a_prop' => [1, 2, 3, 4, 5],
        ];

        foreach ($actual as $key => $val) {
            $this->assertEquals(false, $obj->has($key));
        }

        foreach ($actual as $key => $val) {
            $obj->set($key, $val);
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals($actual[$key], $obj->get($key));
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(true, $obj->has($key));
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(true, $obj->delete($key));
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(false, $obj->has($key));
        }
    }

    /**
    *   @test
    */
    public function multiSuccess()
    {
//      $this->markTestIncomplete();

        $obj = new SessionCache('test');

        $actual = [
            'i_prop' => 123,
            's_prop' => 'string',
            'a_prop' => [1, 2, 3, 4, 5],
        ];

        $this->assertEquals(true, $obj->setMultiple($actual));

        foreach ($actual as $key => $val) {
            $this->assertEquals(true, $obj->has($key));
        }

        $this->assertEquals($actual, $obj->getMultiple(array_keys($actual)));

        $obj->deleteMultiple(array_keys($actual));

        foreach ($actual as $key => $val) {
            $this->assertEquals(false, $obj->has($key));
        }
    }

    /**
    *   @test
    */
    public function getFailure()
    {
//      $this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new SessionCache('test');
        $obj->get(12);
    }

    /**
    *   @test
    */
    public function setFailure()
    {
//      $this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new SessionCache('test');
        $obj->set(12, 13);
    }

    /**
    *   @test
    */
    public function deleteFailure()
    {
//      $this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new SessionCache('test');
        $obj->delete(12);
    }

    /**
    *   @test
    */
    public function getMultipleFailure()
    {
//      $this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new SessionCache('test');
        $obj->getMultiple(12);
    }

    /**
    *   @test
    */
    public function setMultipleFailure()
    {
//      $this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new SessionCache('test');
        $obj->setMultiple(12, 13);
    }

    /**
    *   @test
    */
    public function deleteMultipleFailure()
    {
//      $this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new SessionCache('test');
        $obj->deleteMultiple(12);
    }
}
