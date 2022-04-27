<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\ValueReturnFunction;

class ValueReturnFunctionTest extends ConcertoTestCase
{
    public function functionListProvider()
    {
        return [
            [
                [
                    'array_key_exists',
                    'array_key_first',
                    'array_key_last',
                    'array_map',
                    'array_product',
                    'array_rand',
                    'array_reduce',
                    'array_search',
                    'array_sum',
                    'count',
                    'in_array',
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

        $obj = new ValueReturnFunction();
        $this->assertEquals(
            $expect,
            $obj->functionList(),
        );
    }

    public function hasProvider()
    {
        return [
            [
                'array_key_first',
                true,
            ],
            [
                'count',
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

        $obj = new ValueReturnFunction();
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
                'array_key_first',
                ['A' => 1, 'B' => 2, 'C' => 3],
                [],
                'A',
                null,
            ],
            //has another argument
            [
                'array_reduce',
                range(1, 10, 1),
                [
                    fn($carry, $item) => $carry + $item,
                    10,
                ],
                65,
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

        $obj = new ValueReturnFunction();
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
