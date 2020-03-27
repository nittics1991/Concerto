<?php

declare(strict_types=1);

namespace Concerto\test\task;

use Concerto\pattern\FilesystemIteratorSubject;
use Concerto\test\ConcertoTestCase;

class FilesystemIteratorSubjectTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }
    
    /**
    *   ファイル存在例外
    *
    *   @test
    */
    public function constructException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('PATH not found');
        $object = new FilesystemIteratorSubject('DUMY');
    }
    
    /**
    *   基本処理確認
    *
    *   @test
    **/
    public function basic()
    {
//      $this->markTestIncomplete();
        
        $object = new FilesystemIteratorSubject(__DIR__ . "\\data\\FilesystemIteratorSubject");
        $object_map = $object->toArray();
        
        $object_names = array();
        foreach ((array)$object_map as $obj) {
            $object_names[] = get_class($obj);
        }
        
        $expect = array(
            'Concerto\test\pattern\data\FilesystemIteratorSubject\Alpha',
            'Concerto\test\pattern\data\FilesystemIteratorSubject\Beta',
            'Concerto\test\pattern\data\FilesystemIteratorSubject\Gamma',
            'Concerto\test\pattern\data\FilesystemIteratorSubject\Delta',
            'Concerto\test\pattern\data\FilesystemIteratorSubject\Epsilon'
        );
        $this->assertEquals([], array_diff($object_names, $expect));
        
        $actual = $object->notify();
        $this->assertEquals([], array_diff($actual, $expect));
    }
}
