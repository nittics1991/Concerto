<?php

declare(strict_types=1);

namespace test\Concerto\mbstring;

use test\Concerto\ConcertoTestCase;
use candidate\filesystem\Path;

class PathTest extends ConcertoTestCase
{
    public function resolveProvider()
    {
        $ds = DIRECTORY_SEPARATOR;

        return [
            [
                "/var/log/db/message",
                "{$ds}var{$ds}log{$ds}db{$ds}message",
            ],
            [
                "/var/log/db/message/../change",
                "{$ds}var{$ds}log{$ds}db{$ds}change",
            ],
            [
                '\\var\\log\\db\\message\\..\\change',
                "{$ds}var{$ds}log{$ds}db{$ds}change",
            ],
            [
                '\\var/log\\db/message\\..\\./change',
                "{$ds}var{$ds}log{$ds}db{$ds}change",
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider resolveProvider
    */
    public function resolve($data, $expect)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($expect, Path::resolve($data));
    }
}
