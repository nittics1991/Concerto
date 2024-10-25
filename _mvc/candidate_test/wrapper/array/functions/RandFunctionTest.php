<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class RandFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'rand';

    public function executeProvider()
    {
        $array1 = range(1, 10, 1);
        $array2 = range(11, 20, 1);

        return [
            [
                $array1,
                [],
                true,
            ],
            [
                $array1,
                [2],
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider executeProvider
    */
    public function execute(
        $dataset,
        $arguments,
        $expect,
    ) {
        $this->markTestIncomplete();

        $obj = new StandardArrayObject($dataset);
        $result = call_user_func_array(
            [
                $obj,
                $this->function_name
            ],
            $arguments,
        );

        $result = is_object($result) ?
            $result->toArray() : $result;

        foreach ((array)$result as $actual) {
            $this->assertEquals(
                $expect,
                in_array($actual, $dataset),
                "result=" . print_r($result, true)
            );
        }
    }
}
