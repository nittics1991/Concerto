<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\SessionCache;
use Concerto\cache\InvalidArgumentException;

class SessionCacheTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function basicSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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
    */
    #[Test]
    public function multiSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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
}
