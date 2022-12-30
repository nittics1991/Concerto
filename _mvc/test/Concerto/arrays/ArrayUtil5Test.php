<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use Concerto\standard\ArrayUtil;

class ArrayUtil5Test extends ConcertoTestCase
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
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'every'],
            [$array, $collback]
        );
        $this->assertEquals($expect, $actual);
    }
}