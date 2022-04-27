<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\extend;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\StandardArrayObject;
use candidate\wrapper\array\extend\ValueReturnMethodTrait;

class ValueReturnMethodTraitObject1 extends StandardArrayObject
{
    use ValueReturnMethodTrait;
}

class ValueReturnMethodTraitTest extends ConcertoTestCase
{
    public function nthProvider()
    {
        return [
            [
                range(0, 10),
                3,
                3,
            ],
            [
                range('A', 'Z'),
                3,
                'D',
            ],
            [
                range(0, 10),
                -3,
                8,
            ],
            [
                range('A', 'Z'),
                -3,
                'X',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nthProvider
    */
    public function nth(
        $dataset,
        $target,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new ValueReturnMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->nth($target),
        );
    }

    public function firstProvider()
    {
        return [
            [
                range(0, 10),
                0,
            ],
            [
                range('A', 'Z'),
                'A',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider firstProvider
    */
    public function first(
        $dataset,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new ValueReturnMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->first(),
        );
    }

    public function lastProvider()
    {
        return [
            [
                range(0, 10),
                10,
            ],
            [
                range('A', 'Z'),
                'Z',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider lastProvider
    */
    public function last(
        $dataset,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new ValueReturnMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->last(),
        );
    }
}
