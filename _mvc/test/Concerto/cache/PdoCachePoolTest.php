<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use PDO;
use StdClass;
use test\Concerto\ConcertoTestCase;
use Concerto\cache\{
    CacheException,
    CacheItem,
    InvalidArgumentException,
    SqliteCacheFactory,
    PdoCachePool,
};

class PdoCachePoolTest extends ConcertoTestCase
{
    private $adapter;
    private $namespace;
    private $dataset;

    protected function setUp(): void
    {
        $this->adapter = SqliteCacheFactory::create();
        $this->namespace = 'pdotest';
    }

    /**
    *   @test
    */
    public function getNamespace()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = 'pdotest';
        $obj = new PdoCachePool($namespace, $this->adapter);
        $expect = $namespace;
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'namespace'));
        $this->assertEquals($this->adapter, $this->getPrivateProperty($obj, 'adapter'));

        $namespace = 'pdotest2';
        $obj = new PdoCachePool($namespace, $this->adapter, true);
        $expect = $namespace;
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'namespace'));
        $this->assertEquals($this->adapter, $this->getPrivateProperty($obj, 'adapter'));
    }

    /**
    *
    */
    protected function setDummyData()
    {
        $stdobj = new StdClass();
        $stdobj->prop1 = 10;
        $stdobj->prop2 = 'TEXT';

        $this->dataset = [
            "bool" => true,
            "int" =>  20,
            "float" => 30.03,
            "string" => 'STR',
            "array" => [1, 2, 3],
            "object" => $stdobj
        ];

        $namespace = $this->namespace;
        $i = 0;

        foreach ($this->dataset as $key => $val) {
            $this->adapter->set("{$namespace}.{$key}", $val, 600 + $i);
            $i += 100;
        }
    }

    /**
    *   @test
    */
    public function getItem()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDummyData();

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 0, true);
            $this->assertEquals($expect, $obj->getItem($key));
        }
    }

    /**
    *   @test
    */
    public function getItems()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDummyData();

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);

        foreach ($this->dataset as $key => $val) {
            $expect[$key] = new CacheItem($key, $val, 0, true);
        }

        $this->assertEquals($expect, $obj->getItems(array_keys($this->dataset)));
    }

    /**
    *   @test
    */
    public function hasItem()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDummyData();

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(true, $obj->hasItem($key));
        }

        $this->assertEquals(false, $obj->hasItem('DUMMY'));
    }

    /**
    *   @test
    */
    public function clear()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDummyData();

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);

        $obj->clear();

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }
    }

    /**
    *   @test
    */
    public function deleteItem()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDummyData();

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(true, $obj->deleteItem($key));
        }

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }
    }

    /**
    *   @test
    */
    public function deleteItems()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDummyData();

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);

        $this->assertEquals(true, $obj->deleteItems(array_keys($this->dataset)));

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }
    }

    /**
    *   @test
    */
    public function saveDeferred()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);
        $obj->clear();
        $this->setDummyData();

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 100, true);
            $obj->saveDeferred($expect);

            $deferred = $this->getPrivateProperty($obj, 'deferred');
            $this->assertEquals($expect, $deferred[$key]);
        }

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $this->adapter->get($key));
        }
    }

    /**
    *   @test
    */
    public function save()
    {
      // $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);
        $obj->clear();
        $this->setDummyData();

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 100, true);
            $saved = $obj->save($expect);

            $deferred = $this->getPrivateProperty($obj, 'deferred');




            $this->assertEquals(false, array_key_exists($key, $deferred));

            $this->assertEquals(true, $obj->hasItem($key));
        }
    }

    /**
    *   @test
    *   @see 終了処理
    */
    public function destruct()
    {
        $namespace = $this->namespace;
        $obj = new PdoCachePool($namespace, $this->adapter);
        $this->assertEquals(true, $obj->clear());
    }
}
