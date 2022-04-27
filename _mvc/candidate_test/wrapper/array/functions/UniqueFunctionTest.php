<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class UniqueFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'unique';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "red", "b" => "green", "blue", "red"],
                [],
                ["a" => "green", "red",  "blue",],
            ],
            [
                [4, "4", "3", 4, 3, "3"],
                [SORT_STRING],
                [0 => 4, 2 => "3"],
            ],
        ];
    }
}
