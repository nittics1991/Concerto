<?php

declare(strict_types=1);

namespace candidate_test\arrays;

use test\Concerto\ConcertoTestCase;
use candidate_test\arrays\StubArrayUtil;

class ArrayUtil7Test extends ConcertoTestCase
{
    public function isEmptyTableProvider()
    {
        return [
            [
                [],
                true
            ],
            [
                [0, false, [null], []],
                true
            ],
            [
                [0, false, [null], [0, 1]],
                false
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isEmptyTableProvider
    */
    public function isEmptyTable($array, $expect)
    {
//      $this->markTestIncomplete();

        $actual = StubArrayUtil::isEmptyTable($array);
        $this->assertEquals($expect, $actual);
    }

    public function makeColumnFromRowProvider()
    {
        return [
            [
                [
                    ['A' => 10, 'B' => 400, 'C' => 1],
                    ['A' => 20, 'B' => 300, 'C' => 2],
                    ['A' => 30, 'B' => 200, 'C' => 3],
                    ['A' => 40, 'B' => 100, 'C' => 4],
                ],
                function ($row) {
                    return [
                        'AB' => $row['A'] + $row['B'],
                        'AC' => $row['A'] + $row['C'],
                    ];
                },
                [
                    ['AB' => 410, 'AC' => 11],
                    ['AB' => 320, 'AC' => 22],
                    ['AB' => 230, 'AC' => 33],
                    ['AB' => 140, 'AC' => 44],
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider makeColumnFromRowProvider
    */
    public function makeColumnFromRow($array, $callback, $expect)
    {
//      $this->markTestIncomplete();

        $actual = StubArrayUtil::makeColumnFromRow(
            $array,
            $callback,
        );
        $this->assertEquals($expect, $actual);
    }
}
