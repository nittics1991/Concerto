<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\ArrayUtil;

class ArrayUtil4Test extends ConcertoTestCase
{
    /**
    *   TABLE JOIN provider
    *
    */

    public static function providerJoinTable()
    {
        return [
            [
                [
                    [1, '0401', 103],
                    [2, '0402', 101],
                    [3, '0403', 101],
                    [4, '0403', 102],
                    [5, '0404', 104]
                ],
                [
                    [101, 'AA'],
                    [102, 'BB'],
                    [103, 'CC']
                ]
            ]
        ];
    }

    /**
    *
    */
    #[Test]
    #[DataProvider('providerJoinTable')]
    public function joinTable($ar1, $ar2)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //on条件なし
        $actual = ArrayUtil::joinTable($ar1, $ar2, [], 'LEFT', '_');
        $expect = [
            [1, '0401', 103, '0_' => 101, '1_' => 'AA'],
            [1, '0401', 103, '0_' => 102, '1_' => 'BB'],
            [1, '0401', 103, '0_' => 103, '1_' => 'CC'],

            [2, '0402', 101, '0_' => 101, '1_' => 'AA'],
            [2, '0402', 101, '0_' => 102, '1_' => 'BB'],
            [2, '0402', 101, '0_' => 103, '1_' => 'CC'],

            [3, '0403', 101, '0_' => 101, '1_' => 'AA'],
            [3, '0403', 101, '0_' => 102, '1_' => 'BB'],
            [3, '0403', 101, '0_' => 103, '1_' => 'CC'],

            [4, '0403', 102, '0_' => 101, '1_' => 'AA'],
            [4, '0403', 102, '0_' => 102, '1_' => 'BB'],
            [4, '0403', 102, '0_' => 103, '1_' => 'CC'],

            [5, '0404', 104, '0_' => 101, '1_' => 'AA'],
            [5, '0404', 104, '0_' => 102, '1_' => 'BB'],
            [5, '0404', 104, '0_' => 103, '1_' => 'CC']
        ];

        $this->assertEquals($expect, $actual);

        //suffixなし
        //添字番号のmergeなのでカラムを上書きしない(array_merge)
        $actual = ArrayUtil::joinTable($ar1, $ar2, [], 'LEFT');
        $expect = [
            [1, '0401', 103, 101, 'AA'],
            [1, '0401', 103, 102, 'BB'],
            [1, '0401', 103, 103, 'CC'],

            [2, '0402', 101, 101, 'AA'],
            [2, '0402', 101, 102, 'BB'],
            [2, '0402', 101, 103, 'CC'],

            [3, '0403', 101, 101, 'AA'],
            [3, '0403', 101, 102, 'BB'],
            [3, '0403', 101, 103, 'CC'],

            [4, '0403', 102, 101, 'AA'],
            [4, '0403', 102, 102, 'BB'],
            [4, '0403', 102, 103, 'CC'],

            [5, '0404', 104, 101, 'AA'],
            [5, '0404', 104, 102, 'BB'],
            [5, '0404', 104, 103, 'CC']
        ];

        $this->assertEquals($expect, $actual);

        //innerでjoinしても同じ
        $actual = ArrayUtil::joinTable($ar1, $ar2, [], 'inner');
        $this->assertEquals($expect, $actual);

        //on条件付加
        $actual = ArrayUtil::joinTable($ar1, $ar2, [[2, 0]], 'left');
        $expect = [
            [1, '0401', 103, 103, 'CC'],
            [2, '0402', 101, 101, 'AA'],
            [3, '0403', 101, 101, 'AA'],
            [4, '0403', 102, 102, 'BB'],
            [5, '0404', 104, null, null]
        ];

        $this->assertEquals($expect, $actual);

        $actual = ArrayUtil::joinTable($ar1, $ar2, [[2, 0]], 'inner');
        $expect = [
            [1, '0401', 103, 103, 'CC'],
            [2, '0402', 101, 101, 'AA'],
            [3, '0403', 101, 101, 'AA'],
            [4, '0403', 102, 102, 'BB']
        ];

        $this->assertEquals($expect, $actual);
    }

    /**
    *
    */
    #[Test]
    public function mergeKeepKey()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $x = [
            'AA' => 'aa',   //残
            'BB' => 'bb',   //残
            '012' => 012,   //残
            012 => '8進',  //キーを8進数=>10進数
            345 => 345,     //キー10進数の結果上書きされる
            014 => '8進2'  //キーを8進数=>10進数の結果10は上書きされる
        ];

        $y = [
            0,              //残
            1,              //残
            '345' => 'new',     //残
            12 => 'ZZZ',    //残
            '012' => 'max',     //残
            13,                 //キーを数値キー最大+1
            14              //キーを数値キー最大+2
        ];

        $expect = [
            'AA' => 'aa',   //x
            'BB' => 'bb',   //x
            '012' => 'max',     //y
            10 => '8進',   //x
            345 => 'new',   //y
            12 => 'ZZZ',    //y
            0 => 0,             //y
            1 => 1,             //y
            346 => 13,      //y
            347 => 14       //y
        ];
        $this->assertEquals($expect, ArrayUtil::mergeKeepKey($x, $y));
    }
}
