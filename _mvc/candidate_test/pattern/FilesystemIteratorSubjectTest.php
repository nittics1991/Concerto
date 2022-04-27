<?php

declare(strict_types=1);

namespace test\Concerto\task;

use candidate\pattern\FilesystemIteratorSubject;
use test\Concerto\ConcertoTestCase;

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
    */
    public function basic()
    {
//      $this->markTestIncomplete();

        $object = new FilesystemIteratorSubject(
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'FilesystemIteratorSubject'],
            )
        );
        $object_map = $object->toArray();

        $object_names = [];
        foreach ((array)$object_map as $obj) {
            $object_names[] = get_class($obj);
        }

        $expect = [
            'test\Concerto\pattern\data\FilesystemIteratorSubject\Alpha',
            'test\Concerto\pattern\data\FilesystemIteratorSubject\Beta',
            'test\Concerto\pattern\data\FilesystemIteratorSubject\Gamma',
            'test\Concerto\pattern\data\FilesystemIteratorSubject\Delta',
            'test\Concerto\pattern\data\FilesystemIteratorSubject\Epsilon'
        ];
        $this->assertEquals([], array_diff($object_names, $expect));

        $actual = $object->notify();
        $this->assertEquals([], array_diff($actual, $expect));
    }
}
