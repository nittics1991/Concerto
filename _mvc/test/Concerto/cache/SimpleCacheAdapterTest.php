<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\{
    SqliteCacheFactory,
    PdoCachePool,
    SimpleCacheAdapter
};

class SimpleCacheAdapterTest extends ConcertoTestCase
{
    private $object;
    private $adapter;
    private $namespace = 'simplecache';

    protected function setUp(): void
    {
        $this->adapter = SqliteCacheFactory::create();
        $cachepool = new PdoCachePool($this->namespace, $this->adapter);
        $this->object = new SimpleCacheAdapter($cachepool);
    }

    public static function setProvider()
    {
        $obj = new \StdClass();
        $obj->prop = 'PROP';

        return [
            ['b_data', true, null],
            ['i_data', 12, 60],
            ['f_data', 4, new \DateInterval('PT100S')],
            ['s_data', 'STRING', 10],
            ['a_data', [1, 2, 3, 4, 5], 10],
            ['o_data', $obj, 10],
            ['n_data', null, 10],
            ['b2_data', false, 10],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('setProvider')]
    public function setGet($key, $val, $ttl)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->object->set($key, $val, $ttl);
        $actual = unserialize($this->adapter->get("{$this->namespace}.{$key}"));
        $this->assertEquals($val, $actual);
        $this->assertEquals($val, $this->object->get($key));
        $this->assertEquals(true, $this->object->has($key));
    }

    /**
    */
    #[Test]
    public function multi()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->object->set('ar', [1, 2, 3], 10);

        //not has key
        $this->assertEquals(123, $this->object->get('DUMMY', 123));

        //set multi
        $data = [
            'key1' => 'VAL1',
            'key2' => 'VAL2',
            'key3' => 'VAL3',
            'key4' => 'VAL4',
            'key5' => 'VAL5',
        ];
        $this->object->setMultiple($data, 10);

        foreach ($data as $key => $val) {
            $this->assertEquals($val, $this->object->get($key));
        }

        //get multi
        $this->assertEquals($data, $this->object->getMultiple(array_keys($data)));

        //delete
        $this->assertEquals(true, $this->object->has('ar'));
        $this->object->delete('ar');
        $this->assertEquals(false, $this->object->has('ar'));

        //delete multi
        $this->object->deleteMultiple(['key2', 'key1']);
        $this->assertEquals(false, $this->object->has('key2'));
        $this->assertEquals(false, $this->object->has('key1'));

        //clear
        $this->object->clear();
        foreach ($data as $key => $val) {
            $this->assertEquals(false, $this->object->has($key));
        }
    }

    /**
    *   @see 終了処理
    */
    #[Test]
    public function destruct()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(true, $this->object->clear());
    }
}
