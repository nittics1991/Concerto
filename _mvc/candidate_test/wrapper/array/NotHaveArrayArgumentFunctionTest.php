<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\NotHaveArrayArgumentFunction;

class NotHaveArrayArgumentFunctionTest extends ConcertoTestCase
{
    public function functionListProvider()
    {
        return [
            [
                [
                'array_fill',
                'range',
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

        $obj = new NotHaveArrayArgumentFunction();
        $this->assertEquals(
            $expect,
            $obj->functionList(),
        );
    }

    public function hasProvider()
    {
        return [
            [
                'array_fill',
                true,
            ],
            [
                'range',
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

        $obj = new NotHaveArrayArgumentFunction();
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
            [
                'array_fill',
                [],
                [
                    1,
                    5,
                    'A'
                ],
                [
                    1 => 'A',
                    2 => 'A',
                    3 => 'A',
                    4 => 'A',
                    5 => 'A',
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

        $obj = new NotHaveArrayArgumentFunction();
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
