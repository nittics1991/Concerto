<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\extend;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\StandardArrayObject;
use candidate\wrapper\array\extend\CheckMethodTrait;

class CheckMethodTraitObject1 extends StandardArrayObject
{
    use CheckMethodTrait;
}

class CheckMethodTraitTest extends ConcertoTestCase
{
    public function anyProvider()
    {
        return [
            [
                range('A', 'Z'),
                'e',
                false,
            ],
            [
                range('A', 'Z'),
                'E',
                true,
            ],
            [
                array_fill(1, 10, 'A'),
                'a',
                false,
            ],
            [
                array_fill(1, 10, 'A'),
                'A',
                true,
            ],
            [
                array_merge(
                    array_fill(1, 10, 'A'),
                    ['B',],
                    array_fill(1, 10, 'C'),
                ),
                'B',
                true,
            ],
            [
                array_merge(
                    array_fill(1, 10, 'A'),
                    ['B', 'B'],
                    array_fill(1, 10, 'C'),
                ),
                'B',
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider anyProvider
    */
    public function any1(
        $dataset,
        $target,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new CheckMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->any($target),
        );
    }

    public function everyProvider()
    {
        return [
            [
                range('A', 'Z'),
                'E',
                false,
            ],
            [
                array_fill(1, 10, 'a'),
                'a',
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider everyProvider
    */
    public function every1(
        $dataset,
        $target,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new CheckMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->every($target),
        );
    }

    public function isEmptyProvider()
    {
        return [
            [
                range('A', 'Z'),
                false,
            ],
            [
                [],
                true,
            ],
            [
                [[]],
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isEmptyProvider
    */
    public function isEmpty1(
        $dataset,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new CheckMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->isEmpty(),
        );
    }
}
