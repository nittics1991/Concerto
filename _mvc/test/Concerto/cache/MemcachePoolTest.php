<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use Memcache;
use StdClass;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\CacheItem;
use Concerto\cache\MemcachePool;
use Concerto\cache\CacheException;
use Concerto\cache\InvalidArgumentException;

class MemcachePoolTest extends ConcertoTestCase
{
    private $memcache;
    private $namespace;
    private $dataset;

    protected function setUp(): void
    {
        if (!in_array('memcache', get_loaded_extensions())) {
            $this->markTestSkipped('memcache is not loaded');
            return;
        }

        $this->memcache = new Memcache();
        $this->memcache->connect('127.0.0.1', 11211);
        $this->namespace = 'memtest';
    }

    /**
    */
    #[Test]
    public function getNamespace()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = 'memtest';
        $obj = new MemcachePool($namespace, $this->memcache);
        $expect = $namespace;
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'namespace'));
        $this->assertEquals($this->memcache, $this->getPrivateProperty($obj, 'adapter'));
        $this->assertEquals(false, $this->getPrivateProperty($obj, 'compressed'));

        $namespace = 'memtest2';
        $obj = new MemcachePool($namespace, $this->memcache, true);
        $expect = $namespace;
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'namespace'));
        $this->assertEquals($this->memcache, $this->getPrivateProperty($obj, 'adapter'));
        $this->assertEquals(true, $this->getPrivateProperty($obj, 'compressed'));
    }

    /**
    *
    */
    protected function setDumyData()
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
    }

    /**
    *
    */
    protected function setDumyDataNoCompressed()
    {
        $this->setDumyData();
        $namespace = $this->namespace;
        $i = 0;

        foreach ($this->dataset as $key => $val) {
            $this->memcache->set("{$namespace}.{$key}", $val, 0, 600 + $i);
            $i += 100;
        }
    }

    /**
    *
    */
    protected function setDumyDataCompressed()
    {
        $this->setDumyData();
        $namespace = $this->namespace;
        $i = 0;

        foreach ($this->dataset as $key => $val) {
            $this->memcache->set("{$namespace}.{$key}", $val, MEMCACHE_COMPRESSED, 600 + $i);
            $i += 100;
        }
    }

    /**
    */
    #[Test]
    public function getItemNoCompressed()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataNoCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 0, true);
            $this->assertEquals($expect, $obj->getItem($key));
        }
    }

    /**
    */
    #[Test]
    public function getItemsNoCompressed()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataNoCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $expect[$key] = new CacheItem($key, $val, 0, true);
        }

        $this->assertEquals($expect, $obj->getItems(array_keys($this->dataset)));
    }

    /**
    */
    #[Test]
    public function getItemCompressed()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 0, true);
            $this->assertEquals($expect, $obj->getItem($key));
        }
    }

    /**
    */
    #[Test]
    public function getItemsCompressed()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $expect[$key] = new CacheItem($key, $val, 0, true);
        }

        $this->assertEquals($expect, $obj->getItems(array_keys($this->dataset)));
    }

    /**
    */
    #[Test]
    public function hasItem()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(true, $obj->hasItem($key));
        }

        $this->assertEquals(false, $obj->hasItem('DUMMY'));
    }

    /**
    */
    #[Test]
    public function clear()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        $obj->clear();

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }

        $this->setDumyDataCompressed();
        $this->memcache->set("DUMMY", "XYZ", MEMCACHE_COMPRESSED, 600);

        $obj->clear();

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }

        $this->assertEquals("XYZ", $this->memcache->get("DUMMY"));
        $this->memcache->flush();
        $this->assertEquals(false, $this->memcache->get("DUMMY"));
    }

    /**
    */
    #[Test]
    public function deleteItem()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(true, $obj->deleteItem($key));
        }

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }
    }

    /**
    */
    #[Test]
    public function deleteItems()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        $this->assertEquals(true, $obj->deleteItems(array_keys($this->dataset)));

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $obj->hasItem($key));
        }
    }

    /**
    */
    #[Test]
    public function saveDeferred()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);
        $obj->clear();
        $this->setDumyData();

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 100, true);
            $obj->saveDeferred($expect);

            $deferred = $this->getPrivateProperty($obj, 'deferred');
            $this->assertEquals($expect, $deferred[$key]);
        }

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(false, $this->memcache->get($key));
        }
    }

    /**
    */
    #[Test]
    public function save()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);
        $obj->clear();
        $this->setDumyData();

        foreach ($this->dataset as $key => $val) {
            $expect = new CacheItem($key, $val, 100, true);
            $saved = $obj->save($expect);

            $deferred = $this->getPrivateProperty($obj, 'deferred');




            $this->assertEquals(false, array_key_exists($key, $deferred));

            $this->assertEquals(true, $obj->hasItem($key));
        }
    }

    /**
    */
    #[Test]
    public function getKeys()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        $keymap = $obj->getKeys();
        $keys = array_map(
            function ($val) {
                $ar = explode('.', $val);
                array_shift($ar);
                return implode('.', $ar);
            },
            $keymap
        );

        $this->assertEquals([], array_diff(array_keys($this->dataset), $keys));
        $this->assertEquals([], array_diff($keys, array_keys($this->dataset)));
    }

    /**
    */
    #[Test]
    public function getItemInfo()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setDumyDataCompressed();

        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);

        foreach ($this->dataset as $key => $val) {
            $this->assertEquals(['size', 'expiry'], array_keys($obj->getItemInfo($key)));
        }
    }

    /**
    *   @see 終了処理
    */
    #[Test]
    public function destruct()
    {
        $namespace = $this->namespace;
        $obj = new MemcachePool($namespace, $this->memcache);
        $this->assertEquals(true, $obj->clear());
    }
}
