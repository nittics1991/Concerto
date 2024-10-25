<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\PdoCache;

class PdoCacheTest extends ConcertoTestCase
{
    protected $obj;

    protected function setUp(): void
    {
        $pdo = new \PDO(
            $GLOBALS['DB_DSN'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD']
        );

        $this->obj = new PdoCache(
            $pdo,
            'test._pdocache',
            'key',
            'value',
            'expire_at'
        );
    }

    /**
    */
    #[Test]
    public function basicSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = $this->obj;

        $id = 'prop_i';
        $this->assertEquals(false, $obj->has($id));
        $this->assertEquals(null, $obj->get($id));
        $this->assertEquals(999, $obj->get($id, 999));

        $obj->set($id, 123, 10);
        $this->assertEquals(true, $obj->has($id));
        $this->assertEquals(123, $obj->get($id));
        $this->assertEquals(123, $obj->get($id, 999));

        $id2 = 'unsaved';
        $obj->set($id, 999, 0);
        $this->assertEquals(false, $obj->has($id2));
        $this->assertEquals(null, $obj->get($id2));
        $this->assertEquals(999, $obj->get($id2, 999));

        $id3 = 'prop_s';
        $obj->set($id3, 'ABCDEFG', 10);
        $this->assertEquals(true, $obj->has($id3));
        $this->assertEquals('ABCDEFG', $obj->get($id3));
        $this->assertEquals('ABCDEFG', $obj->get($id3, 'DUMMY'));

        $obj->delete($id);
        $this->assertEquals(false, $obj->has($id));

        $keys = ['aaa', 'bbb', 'ccc'];
        $values = ['aaa' => 123, 'bbb' => 456, 'ccc' => 789];
        $nulls = ['aaa' => null, 'bbb' => null, 'ccc' => null];
        $deletes = ['bbb', 'ccc'];
        $deleteds = ['aaa' => 123, 'bbb' => null, 'ccc' => null];

        $obj->setMultiple($values, 10);
        $this->assertEquals($values, $obj->getMultiple($keys));

        $obj->deleteMultiple($deletes);
        $this->assertEquals($deleteds, $obj->getMultiple($keys));

        $obj->clear();
        $this->assertEquals($nulls, $obj->getMultiple($keys));
    }
}
