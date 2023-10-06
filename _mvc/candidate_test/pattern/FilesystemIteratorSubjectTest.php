<?php

declare(strict_types=1);

namespace candidate_test\pattern;

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
            'candidate_test\pattern\data\FilesystemIteratorSubject\Alpha',
            'candidate_test\pattern\data\FilesystemIteratorSubject\Beta',
            'candidate_test\pattern\data\FilesystemIteratorSubject\Gamma',
            'candidate_test\pattern\data\FilesystemIteratorSubject\Delta',
            'candidate_test\pattern\data\FilesystemIteratorSubject\Epsilon'
        ];
        $this->assertEquals([], array_diff($object_names, $expect));

        $actual = $object->notify();
    }
}
