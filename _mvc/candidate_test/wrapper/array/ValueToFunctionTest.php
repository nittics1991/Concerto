<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\ValueToFunction;

class ValueToFunctionTest extends ConcertoTestCase
{
    public function functionListProvider()
    {
        return [
            [
                [
                    'array_change_key_case',
                    'array_chunk',
                    'array_column',
                    'array_count_values',
                    'array_diff',
                    'array_diff_assoc',
                    'array_diff_key',
                    'array_diff_uassoc',
                    'array_diff_ukey',
                    'array_fill_keys',
                    'array_filter',
                    'array_flip',
                    'array_intersect',
                    'array_intersect_assoc',
                    'array_intersect_key',
                    'array_intersect_uassoc',
                    'array_intersect_ukey',
                    'array_keys',
                    'array_merge',
                    'array_merge_recursive',
                    'array_pad',
                    'array_replace',
                    'array_replace_recursive',
                    'array_reverse',
                    'array_slice',
                    'array_udiff',
                    'array_udiff_assoc',
                    'array_udiff_uassoc',
                    'array_uintersect',
                    'array_uintersect_assoc',
                    'array_uintersect_uassoc',
                    'array_unique',
                    'array_values',
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

        $obj = new ValueToFunction();
        $this->assertEquals(
            $expect,
            $obj->functionList(),
        );
    }

    public function hasProvider()
    {
        return [
            [
                'array_change_key_case',
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

        $obj = new ValueToFunction();
        $this->assertEquals(
            $expect,
            $obj->has($function_name),
        );
    }

    public function executeProvider()
    {
        $array1 = range('a', 'z');
        next($array1);

        return [
            //argument is an array only
            [
                'array_keys',
                ['A' => 1, 'B' => 2, 'C' => 3],
                [],
                ['A', 'B', 'C'],
                null,
            ],
            //has another argument
            [
                'array_chunk',
                range(1, 10, 1),
                [
                    6,
                ],
                [
                    range(1, 6, 1),
                    range(7, 10, 1),
                ],
                null,
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

        $obj = new ValueToFunction();
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
