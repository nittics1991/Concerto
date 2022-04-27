<?php

declare(strict_types=1);

namespace test\Concerto\filesystem;

use test\Concerto\ConcertoTestCase;
use candidate\filesystem\WorkDir;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

class WorkDirTest extends ConcertoTestCase
{
    public function setUp(): void
    {
        $this->vfsRoot = vfsStream::setup('root');
        $this->vfsRootPath = vfsStream::url('root');
    }

    /**
    *   @test
    */
    public function createDir()
    {
//      $this->markTestIncomplete();

        $stub = $this->getMockBuilder(WorkDir::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $dirname = $this->vfsRootPath . '/tmp/test';
        $this->setPrivateProperty($stub, 'path', $dirname);
        $this->callPrivateMethod($stub, 'create', []);
        $this->assertFileExists($dirname);
    }

    /**
    *   @test
    */
    public function sonstruct1()
    {
//      $this->markTestIncomplete();

        //standard tmp dir
        $object = new WorkDir();
        $this->assertEquals(sys_get_temp_dir(), $object->get());

        //create specify path
        $dirname = $this->vfsRootPath . '/tmp/test';
        $object = new WorkDir($dirname);
        $this->assertEquals($dirname, $object->get());
        $this->assertFileExists($dirname);
    }

    public function setData1()
    {
        $dir = [
            'tmp' => [
                'a1' => 'data a',
                'b1' => [
                    'b1a' => 'data ba',
                    'b1b' => [
                        'b1ba' => 'data bba',
                    ],
                    'b1c' => 'data bc',
                ],
                'c1' => 'data c',
            ]
        ];

        $this->vfsRoot = vfsStream::setup('root', null, $dir);
        $this->vfsRootPath = vfsStream::url('root');
    }

    /**
    *   @test
    */
    public function clear1()
    {
//      $this->markTestIncomplete();

        $this->setData1();

        $dirname = $this->vfsRootPath;
        $object = new WorkDir($dirname . '/tmp');
        $this->assertFileExists($dirname . '/tmp/b1/b1b/b1ba');

        $object->clear();
        $this->assertFileDoesNotExist($dirname . '/tmp/b1/b1b/b1ba');
        $this->assertFileDoesNotExist($dirname . '/tmp/b1/b1b');
        $this->assertFileDoesNotExist($dirname . '/tmp/b1');
        $this->assertFileExists($dirname . '/tmp');
    }

    /**
    *   @test
    */
    public function delete1()
    {
//      $this->markTestIncomplete();

        $this->setData1();

        $dirname = $this->vfsRootPath;
        $object = new WorkDir($dirname . '/tmp');
        $this->assertFileExists($dirname . '/tmp');

        $object->delete();
        $this->assertFileDoesNotExist($dirname . '/tmp');
    }

    /**
    *   @test
    */
    public function clearBeforeDate()
    {
//      $this->markTestIncomplete();

        $this->setData1();
        $dirname = $this->vfsRootPath;

        touch($dirname . '/tmp/a1', strtotime('-2 day'));
        touch($dirname . '/tmp/b1/b1b', strtotime('-2 day'));  //do not delete DIR
        touch($dirname . '/tmp/c1', strtotime('-2 day'));

        $object = new WorkDir($dirname . '/tmp');
        $object->clearBeforeDate('P1D');

        //degug
         $dirStructure = vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure();

        $this->assertFileDoesNotExist($dirname . '/tmp/a1');
        $this->assertFileExists($dirname . '/tmp/b1/b1b');  //do not delete DIR
        $this->assertFileDoesNotExist($dirname . '/tmp/c1');
        $this->assertFileExists($dirname . '/tmp/b1/b1a');
        $this->assertFileExists($dirname . '/tmp/b1/b1b');
    }

    /**
    *   @test
    */
    public function getIterator()
    {
//      $this->markTestIncomplete();

        $this->setData1();
        $dirname = $this->vfsRootPath;
        $object = new WorkDir($dirname . '/tmp');

        foreach ($object as $key => $val) {
            $this->assertInstanceOf(\SplFileInfo::class, $val);
        }
    }
}
