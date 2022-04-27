<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\ReferToFunction;

class ReferToFunctionTest extends ConcertoTestCase
{
    public function functionListProvider()
    {
        return [
            [
                [
                    'array_multisort',
                    'array_pop',
                    'array_push',
                    'array_shift',
                    'array_splice',
                    'array_unshift',
                    'array_walk',
                    'array_walk_recursive',
                    'arsort',
                    'asort',
                    'krsort',
                    'ksort',
                    'natcasesort',
                    'natsort',
                    'rsort',
                    'shuffle',
                    'sort',
                    'uasort',
                    'uksort',
                    'usort',
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider functionListProvider
    */
    public function functionList($expect)
    {
//      $this->markTestIncomplete();

        $obj = new ReferToFunction();
        $this->assertEquals(
            $expect,
            $obj->functionList(),
        );
    }

    public function hasProvider()
    {
        return [
            [
                'array_shift',
                true,
            ],
            [
                'sort',
                true,
            ],
            [
                'dummy',
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasProvider
    */
    public function has($function_name, $expect)
    {
//      $this->markTestIncomplete();

        $obj = new ReferToFunction();
        $this->assertEquals(
            $expect,
            $obj->has($function_name),
        );
    }

    public function executeProvider()
    {
        return [
            //argument is an array only
            [
                'arsort',
                ['A' => 1, 'B' => 2, 'C' => 3,],
                [],
                ['C' => 3, 'B' => 2, 'A' => 1,],
                null,
            ],
            // has another argument
            [
                'array_walk',
                range(1, 10, 1),
                [
                   fn(&$val, $key) => $val = $val * 10
                ],
                range(10, 100, 10),
                null,
            ],
            //relatedValue
            [
                'array_splice',
                range(1, 10, 1),
                [
                    1,
                    8,
                ],
                [1, 10],
                range(2, 9, 1),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider executeProvider
    */
    public function execute(
        $function_name,
        $data,
        $arguments,
        $expect,
        $expect_related_value,
    ) {
//      $this->markTestIncomplete();

        $obj = new ReferToFunction();
        $this->assertEquals(
            $expect,
            $obj->execute(
                $data,
                $function_name,
                $arguments,
            ),
        );

        $this->assertEquals(
            $expect_related_value,
            $obj->relatedValue(),
        );
    }
}
