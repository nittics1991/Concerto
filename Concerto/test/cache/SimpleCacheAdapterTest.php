<?php

declare(strict_types=1);

namespace Concerto\test\cache;

use Concerto\test\ConcertoTestCase;
use Concerto\cache\SimpleCacheAdapter;
use Concerto\cache\MemcacheFactory;
use Concerto\cache\MemcachePool;

class SimpleCacheAdapterTest extends ConcertoTestCase
{
    private $object;
    private $memcache;
    private $namespace = 'simplecache';

    protected function setUp(): void
    {
        $this->memcache = MemcacheFactory::getConnection();
        $cachepool = new MemcachePool($this->namespace, $this->memcache);
        $this->object = new SimpleCacheAdapter($cachepool);
    }

    public function setProvider()
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
    *   @test
    *   @dataProvider setProvider
    */
    public function setGet($key, $val, $ttl)
    {
//      $this->markTestIncomplete();

        $this->object->set($key, $val, $ttl);
        $actual = unserialize($this->memcache->get("{$this->namespace}.{$key}"));
        $this->assertEquals($val, $actual);
        $this->assertEquals($val, $this->object->get($key));
        $this->assertEquals(true, $this->object->has($key));
    }

    /**
    *   @test
    */
    public function multi()
    {
//      $this->markTestIncomplete();

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
    *   @test
    *   @see 終了処理
    */
    public function destruct()
    {
//      $this->markTestIncomplete();

        //アイテムが無いとfalse
        $this->assertEquals(false, $this->object->clear());
    }
}
