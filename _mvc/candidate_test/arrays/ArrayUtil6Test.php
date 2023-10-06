<?php

declare(strict_types=1);

namespace candidate_test\arrays;

use test\Concerto\ConcertoTestCase;
use candidate_test\arrays\StubArrayUtil;

class ArrayUtil6Test extends ConcertoTestCase
{
    public function someProvider()
    {
        return [
            [
                [1, 'A', '2'],
                function ($key, $val) {
                    return is_int($val);
                },
                true
            ],
            [
                ['a', 'A', 'x'],
                function ($key, $val) {
                    return is_int($val);
                },
                false
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider someProvider
    */
    public function some($array, $collback, $expect)
    {
//      $this->markTestIncomplete();

        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'some'],
            [$array, $collback]
        );
        $this->assertEquals($expect, $actual);
    }

    public function everyProvider()
    {
        return [
            [
                [1, 3, 2],
                function ($key, $val) {
                    return is_int($val);
                },
                true
            ],
            [
                [1, '2', 2],
                function ($key, $val) {
                    return is_int($val);
                },
                false
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider everyProvider
    */
    public function every($array, $collback, $expect)
    {
//      $this->markTestIncomplete();

        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'every'],
            [$array, $collback]
        );
        $this->assertEquals($expect, $actual);
    }

    public function flattenProvider()
    {
        return [
            [
                [
                    'a' => [1, 2, 3],
                    'b' => [11, 12],
                    'c' => [21, 22, 23],
                ],
                1,
                [1, 2, 3, 11, 12, 21, 22, 23]
            ],
            [
                [
                    'a' => [
                        'aa' => [1, 2, 3],
                        'ab' => [11, 12],
                    ],
                    'b' => [
                        'ba' => [21, 22],
                        'bb' => [31, 32, 33],
                    ],
                ],
                1,
                [
                    0 => [1, 2, 3],
                    1 => [11, 12],
                    2 => [21, 22],
                    3 => [31, 32, 33],
                ],
            ],
            [
                [
                    'a' => [1, 2, 3],
                    'b' => [11, 12],
                    'c' => [21, 22, 23],
                ],
                2,
                [1, 2, 3, 11, 12, 21, 22, 23]
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider flattenProvider
    */
    public function flatten($array, $depth, $expect)
    {
//      $this->markTestIncomplete();

        $actual = StubArrayUtil::flatten(
            $array,
            $depth,
        );
        $this->assertEquals($expect, $actual);
    }
}
