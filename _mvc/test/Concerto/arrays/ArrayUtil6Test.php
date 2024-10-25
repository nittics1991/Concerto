<?php

declare(strict_types=1);

namespace test\Concerto\arrays;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\ArrayUtil;

class ArrayUtil6Test extends ConcertoTestCase
{
    public static function fillUpProvider()
    {
        return [
            //数値キー1次元配列 初期値NULL 余り削除
            [
                range('a', 'e'),
                [0,2,4],
                [],
                true,
                [0 => 'a', 2 => 'c', 4 => 'e',],
            ],
            //数値キー1次元配列 初期値NULL 余り残す
            [
                range('a', 'e'),
                [0,2,4],
                [],
                false,
                [
                    0 => 'a', 2 => 'c', 4 => 'e',
                    1 => 'b', 3 => 'd'
                ],
            ],
            //数値キー1次元配列 初期値一部値指定 余り削除
            [
                range('a', 'e'),
                [0,2,4,5],
                [5 => 'f'],
                true,
                [
                    0 => 'a', 2 => 'c', 4 => 'e',
                    5 => 'f'
                ],
            ],
            //数値キー1次元配列 初期値一部callback指定 余り削除
            [
                range('a', 'e'),
                [0,2,4,5],
                [5 => fn($array) => 'f'],
                true,
                [
                    0 => 'a', 2 => 'c', 4 => 'e',
                    5 => 'f'
                ],
            ],
            //文字列キー1次元配列 初期値NULL 余り削除
            [
                array_combine(
                    range('A', 'E'),
                    range('a', 'e'),
                ),
                ['A','C','E'],
                [],
                true,
                ['A' => 'a', 'C' => 'c', 'E' => 'e',],
            ],
            //文字列キー1次元配列 初期値NULL 余り残す
            [
                array_combine(
                    range('A', 'E'),
                    range('a', 'e'),
                ),
                ['A','C','E'],
                [],
                false,
                [
                    'A' => 'a', 'C' => 'c', 'E' => 'e',
                    'B' => 'b', 'D' => 'd',
                ],
            ],
            //文字列キー1次元配列 初期値一部値指定 余り削除
            [
                array_combine(
                    range('A', 'E'),
                    range('a', 'e'),
                ),
                ['A','C','E','F'],
                ['F' => 'f'],
                true,
                [
                    'A' => 'a', 'C' => 'c', 'E' => 'e',
                    'F' => 'f'
                ],
            ],
            //文字列キー1次元配列 初期値一部callable指定 余り削除
            [
                array_combine(
                    range('A', 'E'),
                    range('a', 'e'),
                ),
                ['A','C','E','F'],
                ['F' => fn($array) => 'f'],
                true,
                [
                    'A' => 'a', 'C' => 'c', 'E' => 'e',
                    'F' => 'f'
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('fillUpProvider')]
    public function fillUp(
        array $target,
        array $keys = [],
        array $complement = [],
        bool $rest_removed = true,
        array $expect = [],
    ) {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $this->assertEquals(
            $expect,
            ArrayUtil::fillUp(
                $target,
                $keys,
                $complement,
                $rest_removed,
            ),
        );
    }

    public static function flatRowProvider()
    {
        return [
            //数値キー
            [
                [
                    1,
                    [21, 22],
                    [31, 32, 33],
                    34
                ],
                [
                    [1, 21, 31, 34],
                    [null, 22, 32, null],
                    [null, null, 33, null],
                ],
            ],
            //文字列キー
            //値を複数行に分割した時、行を揃える(Excel出力時に利用)
            [
                [
                    'A' => 1,
                    'B' => [21, 22,],
                    'C' => [31, 32, 33],
                    'D' => 34,
                ],
                [
                    ['A' => 1, 'B' => 21, 'C' => 31, 'D' => 34],
                    ['A' => null, 'B' => 22, 'C' => 32, 'D' => null],
                    ['A' => null, 'B' => null, 'C' => 33, 'D' => null],
                ],
            ],
            //inner文字列キー
            [
                [
                    ['A' => 'a1', 'B' => 'b1',],
                    'C' => 'c2',
                    ['B' => 'b3', 'D' => 'd3'],
                    'Z' => ['A' => 'a4', 'B' => 'b4', 'C' => 'c4'],
                ],
                [
                    [0 => 'a1', 'C' => 'c2', 1 => 'b3', 'Z' => 'a4'],
                    [0 => 'b1', 'C' => null, 1 => 'd3', 'Z' => 'b4'],
                    [0 => null, 'C' => null, 1 => null, 'Z' => 'c4'],
                ],
            ],

        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('flatRowProvider')]
    public function flatRow(
        array $target,
        array $expect,
    ) {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $this->assertEquals(
            $expect,
            ArrayUtil::flatRow(
                $target,
            ),
        );
    }
}
