<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use Closure;
use StdClass;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\cache\CacheItem;
use Concerto\cache\CacheItemPool;
use Concerto\cache\InvalidArgumentException;

class Pool extends CacheItemPool
{
    protected function fetch(array $ids): array
    {
        $result = [];
        $i = 0;

        foreach ($ids as $id) {
            $keys = explode('.', $id);

            if (mb_ereg_match('^[0-9]+$', $keys[1])) {
                throw new InvalidArgumentException("id must be string");
            }

            $result[$id] = "{$id}_{$i}";
            $i++;
        }
        return $result;
    }

    protected function doClear(): bool
    {
        return true;
    }

    protected function doDelete(array $ids): bool
    {
        return true;
    }

    protected function doSave(): array
    {
        return $this->deferred;
    }
}



class CacheItemPoolTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    /**
    */
    #[Test]
    public function getNamespace()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = 'name1';
        $obj = new Pool($expect);
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'namespace'));
    }

    /**
    */
    #[Test]
    public function makeId()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = 'name1';
        $obj = new Pool($namespace);

        $name = 'test1';
        $expect = "{$namespace}.{$name}";
        $this->assertEquals($expect, $this->callPrivateMethod($obj, 'makeId', [$name]));
    }

    /**
    */
    #[Test]
    public function createItems()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $namespace = 'name1';
        $obj = new Pool($namespace);

        $stdobj = new StdClass();
        $stdobj->prop1 = 10;
        $stdobj->prop2 = 'TEXT';

        $dataset = [
            "{$namespace}.bool" => false,
            "{$namespace}.int" => 10,
            "{$namespace}.float" => 20.02,
            "{$namespace}.string" => 'STR',
            "{$namespace}.array" => [1, 2, 3],
            "{$namespace}.object" => $stdobj
        ];

        $bool = new CacheItem('bool', $dataset["{$namespace}.bool"], 0, true);
        $int = new CacheItem('int', $dataset["{$namespace}.int"], 0, true);
        $float = new CacheItem('float', $dataset["{$namespace}.float"], 0, true);
        $string = new CacheItem('string', $dataset["{$namespace}.string"], 0, true);
        $array = new CacheItem('array', $dataset["{$namespace}.array"], 0, true);
        $object = new CacheItem('object', $dataset["{$namespace}.object"], 0, true);

        $expect = compact("bool", "int", "float", "string", "array", "object");
        $this->assertEquals($expect, $this->callPrivateMethod($obj, 'createItems', [$dataset]));
    }

    /**
    */
    #[Test]
    public function ExceptionConstruct()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('max length is char(20):name12345678901234567890');
        $expect = 'name12345678901234567890';
        $obj = new Pool($expect);
    }

    /**
    */
    #[Test]
    public function getItem()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $expect = new CacheItem('prop1', 'name1.prop1_0', 0, true);
        $this->assertEquals($expect, $obj->getItem('prop1'));
    }

    /**
    */
    #[Test]
    public function ExceptionGetItem()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('id must be string');
        $obj = new Pool('name1');
        $obj->getItem('12');
    }

    /**
    */
    #[Test]
    public function getItems()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $expect['prop1'] = new CacheItem('prop1', 'name1.prop1_0', 0, true);
        $expect['prop2'] = new CacheItem('prop2', 'name1.prop2_1', 0, true);
        $expect['prop3'] = new CacheItem('prop3', 'name1.prop3_2', 0, true);
        $this->assertEquals($expect, $obj->getItems(['prop1', 'prop2', 'prop3']));

        //phpunit11 mockが動かない
        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('Concerto\cache\CacheItemPool')
                // ->setConstructorArgs(['name2'])
                // ->getMock();

            // $mock
                // ->method('fetch')
                // ->withConsecutive([
                    // $this->equalTo(['name2.prop1', 'name2.prop2', 'name2.prop3'])
                // ]);

            // $mock->getItems(['prop1', 'prop2', 'prop3']);
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();
    }

    /**
    */
    #[Test]
    public function hasItem()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $this->assertEquals(true, $obj->hasItem('prop1'));
        $this->assertEquals(false, $obj->hasItem('12'));

        //phpunit11 mockが動かない
        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('Concerto\cache\CacheItemPool')
                // ->setConstructorArgs(['name2'])
                // ->getMock();

            // $mock
                // ->method('fetch')
                // ->withConsecutive([
                    // $this->equalTo(['name2.prop1'])
                // ]);

            // $mock->hasItem('prop1');
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();
    }

    /**
    */
    #[Test]
    public function clear()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $this->setPrivateProperty($obj, 'deferred', [1, 2, 3, 4]);
        $this->assertEquals(true, $obj->clear());
        $this->assertEquals([], $this->getPrivateProperty($obj, 'deferred'));

        //phpunit11 mockが動かない
        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('Concerto\cache\CacheItemPool')
                // ->setConstructorArgs(['name2'])
                // ->getMock();

            // $mock
                // ->method('doClear');

            // $mock->clear();
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();
    }

    /**
    */
    #[Test]
    public function deleteItem()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $this->assertEquals(true, $obj->deleteItem('prop1'));
    }

    /**
    */
    #[Test]
    public function deleteItems()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $this->assertEquals(true, $obj->deleteItems(['prop1', 'prop2', 'prop3']));

        //phpunit11 mockが動かない
        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('Concerto\cache\CacheItemPool')
                // ->setConstructorArgs(['name2'])
                // ->getMock();

            // $mock
                // ->method('doDelete')
                // ->withConsecutive([
                    // $this->equalTo(['name2.prop1', 'name2.prop2', 'name2.prop3'])
                // ]);

            // $mock->deleteItems(['prop1', 'prop2', 'prop3']);
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();
    }

    /**
    */
    #[Test]
    public function saveDeferred()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');

        $item = new CacheItem('array', [1,2,3,4], 1234, true);
        $obj->saveDeferred($item);
        $expect = ['array' => $item];
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'deferred'));

        $item2 = new CacheItem('array2', [11,12,13,14], 9876, true);
        $obj->saveDeferred($item2);
        $expect = ['array' => $item, 'array2' => $item2];
        $this->assertEquals($expect, $this->getPrivateProperty($obj, 'deferred'));
    }

    /**
    */
    #[Test]
    public function commit()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Pool('name1');
        $this->assertEquals(true, $obj->commit());

        //phpunit11 mockが動かない
        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('test\Concerto\cache\Pool')
                // ->setConstructorArgs(['name1'])
                // ->setMethods(['doSave'])
                // ->getMock();

            // $mock->deferred = ['prop1' => 11, 'prop2' => 12];

            // $mock
                // ->method('doSave')
                // ->will($this->returnValue(['prop1', 'prop2']));

            // $this->assertEquals(true, $mock->commit());
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();

        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('test\Concerto\cache\Pool')
                // ->setConstructorArgs(['name1'])
                // ->setMethods(['doSave'])
                // ->getMock();

            // $mock->deferred = ['prop1' => 11, 'prop2' => 12];

            // $mock
                // ->method('doSave')
                // ->will($this->returnValue(['prop2']));

            // $this->assertEquals(false, $mock->commit());
            // $this->assertEquals(['prop1' => 11], $mock->deferred);
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();
    }

    /**
    */
    #[Test]
    public function save()
    {
     $this->markTestIncomplete('--- phpunit11 mock動かない ---');

        //phpunit11 mockが動かない
        // Closure::bind(function () {
            // $mock = $this->getMockBuilder('test\Concerto\cache\Pool')
                // ->setConstructorArgs(['name1'])
                // ->setMethods(['commit'])
                // ->getMock();

            // $mock->deferred = ['prop1' => 11, 'prop2' => 12];

            // $mock
                // ->method('commit')
                // ->will($this->returnValue(false));

            // $item = new CacheItem('id', [1,2,3], 0, true);

            // $this->assertEquals(false, $mock->save($item));
            // $this->assertEquals(['prop1' => 11, 'prop2' => 12, 'id' => $item], $mock->deferred);
        // }, $this, 'test\Concerto\cache\Pool')->__invoke();
    }
}
