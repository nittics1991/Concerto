<?php

declare(strict_types=1);

namespace candidate_test\arrays;

use test\Concerto\ConcertoTestCase;
use candidate_test\arrays\StubArrayUtil;

class ArrayUtil2Test extends ConcertoTestCase
{
    /**
    *
    *   @test
    */
    public function initArray()
    {
//      $this->markTestIncomplete();

        $actual = StubArrayUtil::initArray('z', ['X', 'Y'], ['A', 'B', 'C'], [1, 2]);
        $expect = [
            'X' => [
                'A' => [
                    1 => 'z',
                    2 => 'z'
                ],
                'B' => [
                    1 => 'z',
                    2 => 'z'
                ],
                'C' => [
                    1 => 'z',
                    2 => 'z'
                ]
            ],
            'Y' => [
                'A' => [
                    1 => 'z',
                    2 => 'z'
                ],
                'B' => [
                    1 => 'z',
                    2 => 'z'
                ],
                'C' => [
                    1 => 'z',
                    2 => 'z'
                ]
            ]
        ];
        $this->assertEquals($expect, $actual);

        $actual = StubArrayUtil::initArray(null, ['A', 'B', 'C'], [1, 2]);
        $expect =  [
            'A' => [
                1 => null,
                2 => null
            ],
            'B' => [
                1 => null,
                2 => null
            ],
            'C' => [
                1 => null,
                2 => null
            ]
        ];
        $this->assertEquals($expect, $actual);
    }

    /**
    *
    *   @test
    */
    public function pivot()
    {
//      $this->markTestIncomplete();

        $x = [
            ['mon' => '1', 'tgt' => 'tel', 'c' => 13, 'd' => 14, 'e' => 21, 'tanto' => 'A'],
            ['mon' => '1', 'tgt' => 'tel', 'c' => 11, 'd' => 12, 'e' => 22, 'tanto' => 'A'],
            ['mon' => '2', 'tgt' => 'adr', 'c' => 15, 'd' => 16, 'e' => 23, 'tanto' => 'B'],
            ['mon' => '2', 'tgt' => 'tel', 'c' => 17, 'd' => 18, 'e' => 24, 'tanto' => 'B'],
            ['mon' => '2', 'tgt' => 'tel', 'c' => 19, 'd' => 20, 'e' => 25, 'tanto' => 'A']
        ];

        $expect = [
            ['tgt' => 'mon', 1 => '1', 2 => '2'],
            'c' => [
                ['tgt' => 'adr', 1 => 0,  2 => 15],
                ['tgt' => 'tel', 1 => 24, 2 => 36]
            ],
            'e' => [
                ['tgt' => 'adr', 1 => 1, 2 => 23],
                ['tgt' => 'tel', 1 => 462, 2 => 600]
            ]
        ];

        $actual = StubArrayUtil::pivot(
            $x,
            'tgt',
            'mon',
            [
                'c' => 'array_sum',
                'e' => 'array_product'
            ]
        );

        $this->assertEquals($expect, $actual);


        $expect = [
            ['tgt' => 'tanto', 'A' => 'A', 'B' => 'B'],
            'c' => [
                ['tgt' => 'adr', 'A' => 0,  'B' => 15],
                ['tgt' => 'tel', 'A' => 43, 'B' => 17]
            ],
        ];

        $actual = StubArrayUtil::pivot(
            $x,
            'tgt',
            'tanto',
            [
                'c' => 'array_sum',
            ]
        );

        $this->assertEquals($expect, $actual);
    }

    /**
    *
    *   @test
    */
    public function max()
    {
//      $this->markTestIncomplete();

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(16, StubArrayUtil::max($data));

        $data = [11, 2, 3, 3.14, 16, 'A', false, true];
        $this->assertEquals(16, StubArrayUtil::max($data));

        $data = [];
        $this->assertEquals(null, StubArrayUtil::max($data));

        $data = [11, 'A', 'C', 'AA', 'B'];
        $this->assertEquals('C', StubArrayUtil::max($data, SORT_NATURAL));

        //
        $this->assertEquals(null, StubArrayUtil::max([], SORT_NATURAL));
    }

    /**
    *
    *   @test
    */
    public function min()
    {
//      $this->markTestIncomplete();

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(2, StubArrayUtil::min($data));

        $data = [11, 2, 3, 3.14, 16, 'A', false, true];
        // $this->assertEquals(false, StubArrayUtil::min($data));
        $this->assertEquals('A', StubArrayUtil::min($data));

        $data = [];
        $this->assertEquals(null, StubArrayUtil::min($data));

        $data = ['D', 'A', 'C', 'AA', 'B'];
        $this->assertEquals('A', StubArrayUtil::min($data, SORT_NATURAL));

        $this->assertEquals(null, StubArrayUtil::min([], SORT_NATURAL));
    }

    /**
    *
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(11, StubArrayUtil::first($data));

        $data = [];
        $this->assertEquals(null, StubArrayUtil::first($data));
    }

    /**
    *
    *   @test
    */
    public function last()
    {
//      $this->markTestIncomplete();

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(4, StubArrayUtil::last($data));

        $data = [];
        $this->assertEquals(null, StubArrayUtil::last($data));
    }
}
