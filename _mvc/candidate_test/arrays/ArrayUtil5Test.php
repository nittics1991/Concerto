<?php

declare(strict_types=1);

namespace candidate_test\arrays;

use test\Concerto\ConcertoTestCase;
use candidate_test\arrays\StubArrayUtil;

class ArrayUtil5Test extends ConcertoTestCase
{
    public function keyRemapProvider()
    {
        return [
            [
                [
                    'tanto_code' => '12345',
                    'tanto_name' => 'ABC',
                    'bumon_code' => 2,
                    'bumon_name' => 'XXX'
                ],
                ['tanto_code', 'bumon_code'],
                ['cd_tanto', 'cd_bumon'],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider keyRemapProvider
    */
    public function keyRemap($data, $serKey, $destKey)
    {
//      $this->markTestIncomplete();

        $expect = [
            'cd_tanto' => '12345',
            'cd_bumon' => 2
        ];

        $this->assertEquals($expect, StubArrayUtil::keyRemap($data, $serKey, $destKey));
    }

    /**
    *   @test
    *   @dataProvider keyRemapProvider
    */
    public function keyPertiallyRemap($data, $serKey, $destKey)
    {
//      $this->markTestIncomplete();

        $expect = [
            'cd_tanto' => '12345',
            'tanto_name' => 'ABC',
            'cd_bumon' => 2,
            'bumon_name' => 'XXX'
        ];

        $this->assertEquals($expect, StubArrayUtil::keyPartiallyRemap($data, $serKey, $destKey));
    }

    public function replaceWithoutProvider()
    {
        return [
            [
                [null, '', 0],
                [
                    [1,2,3,4,5,6,7,8,9,0],
                    [0,4,7,9,5,3,1,6,8,2],
                    [9,8,7,6,5,4,3,2,1,0]
                ],
                [9,8,7,6,5,4,3,2,1,2]
            ],
            [
                [null],
                [
                    [1,2,3,4,5],
                    [9,null,7,6,5,4,3]
                ],
                [9,2,7,6,5,4,3]
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider replaceWithoutProvider
    */
    public function replaceWithout($exclude, $dataset, $expect)
    {
//      $this->markTestIncomplete();

        array_unshift($dataset, $exclude);
        $actual = forward_static_call_array(
            ['candidate_test\arrays\StubArrayUtil', 'replaceWithout'],
            $dataset
        );
        $this->assertEquals($expect, $actual);
    }

    public function replaceInitParamProvider()
    {
        return [
            [
                [
                    ['a' => 1, 'b' => 2, 'c' => 3, 4, 5],
                    ['x'  => 11, 'a' => 12, 'Y' => 13, 'c' => 14],
                    ['c' => 21, 22, 'y' => 23, '1' => 24],
                ],
                ['a' => 12, 'b' => 2, 'c' => 21, 22, 24]
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider replaceInitParamProvider
    */
    public function replaceInitParam($dataset, $expect)
    {
//      $this->markTestIncomplete();

        $actual = call_user_func_array(
            ['candidate_test\arrays\StubArrayUtil', 'replaceInitParam'],
            $dataset
        );
        $this->assertEquals($expect, $actual);
    }
}
