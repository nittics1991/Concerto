<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use Concerto\cache\SqliteCacheFactory;

class SqliteCacheTest extends ConcertoTestCase
{
    protected $obj;

    protected function setUp(): void
    {
    }

    /**
    *
    */
    public function main()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->obj = SqliteCacheFactory::create();
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

        $obj->delete($id);
        $this->assertEquals(false, $obj->has($id));

        $id3 = 'prop_s';
        $obj->set($id3, 'ABCDEFG', 10);
        $this->assertEquals(true, $obj->has($id3));
        $this->assertEquals('ABCDEFG', $obj->get($id3));
        $this->assertEquals('ABCDEFG', $obj->get($id3, 'DUMMY'));

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

    /**
    *   @test
    */
    public function createByDefault()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->obj = SqliteCacheFactory::create();
        $this->main();
    }

    /**
    *   @test
    */
    public function createSpecifyFile()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $tempPath = getenv('TEMP');
        $tempPath = $tempPath === false ?
            '/tmp' :
            $tempPath;

        $filePath =
            $tempPath .
            DIRECTORY_SEPARATOR .
            'SqliteCacheTest.createSpecifyFile.sqlite';

        $this->obj = SqliteCacheFactory::create(
            $filePath,
        );
        $this->main();
    }

    /**
    *   @test
    */
    public function constructByDefault()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->obj = new SqliteCacheFactory();
        $this->main();
    }

    /**
    *   @test
    */
    public function constructWithArgument()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $tempPath = getenv('TEMP');
        $tempPath = $tempPath === false ?
            '/tmp' :
            $tempPath;

        $filePath =
            $tempPath .
            DIRECTORY_SEPARATOR .
            'SqliteCacheTest.constructWithArgument.sqlite';

        $this->obj = new SqliteCacheFactory(
            $filePath,
            'name',
            'val',
            'exp',
        );
        $this->main();
    }
}
