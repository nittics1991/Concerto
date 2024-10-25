<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\ArrayUtil;

class ArrayUtil2Test extends ConcertoTestCase
{
    /**
    *
    */
    #[Test]
    public function pivot()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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

        $actual = ArrayUtil::pivot(
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

        $actual = ArrayUtil::pivot(
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
    */
    #[Test]
    public function max()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(16, ArrayUtil::max($data));

        $data = [11, 2, 3, 3.14, 16, 'A', false, true];
        $this->assertEquals(16, ArrayUtil::max($data));

        $data = [];
        $this->assertEquals(null, ArrayUtil::max($data));

        $data = [11, 'A', 'C', 'AA', 'B'];
        $this->assertEquals('C', ArrayUtil::max($data, SORT_NATURAL));

        //
        $this->assertEquals(null, ArrayUtil::max([], SORT_NATURAL));
    }

    /**
    *
    */
    #[Test]
    public function min()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(2, ArrayUtil::min($data));

        $data = [11, 2, 3, 3.14, 16, 'A', false, true];
        // $this->assertEquals(false, ArrayUtil::min($data));
        $this->assertEquals('A', ArrayUtil::min($data));

        $data = [];
        $this->assertEquals(null, ArrayUtil::min($data));

        $data = ['D', 'A', 'C', 'AA', 'B'];
        $this->assertEquals('A', ArrayUtil::min($data, SORT_NATURAL));

        $this->assertEquals(null, ArrayUtil::min([], SORT_NATURAL));
    }

    /**
    *
    */
    #[Test]
    public function first()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(11, ArrayUtil::first($data));

        $data = [];
        $this->assertEquals(null, ArrayUtil::first($data));
    }

    /**
    *
    */
    #[Test]
    public function last()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [11, 2, 3, 3.14, 16, 4];
        $this->assertEquals(4, ArrayUtil::last($data));

        $data = [];
        $this->assertEquals(null, ArrayUtil::last($data));
    }
}
