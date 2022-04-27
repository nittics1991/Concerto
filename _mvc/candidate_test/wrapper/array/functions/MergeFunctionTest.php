<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class MergeFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'merge';

    public function executeProvider()
    {
        return [
            [
                ["color" => "red", 2, 4],
                [
                    ["a", "b", "color" => "green", "shape" => "trapezoid", 4],
                ],
                [
                    'color' => 'green',
                    0 => 2,
                    1 => 4,
                    2 => 'a',
                    3 => 'b',
                    'shape' => 'trapezoid',
                    4 => 4,
                ],
            ],
        ];
    }
}
