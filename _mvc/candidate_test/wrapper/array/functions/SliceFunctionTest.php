<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class SliceFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'slice';

    public function executeProvider()
    {
        return [
            [
                ["a", "b", "c", "d", "e"],
                [2],
                ['c', 'd', 'e',],
            ],
            [
                ["a", "b", "c", "d", "e"],
                [-2, 1],
                ['d',],
            ],
            [
                ["a", "b", "c", "d", "e"],
                [0, 3],
                ["a", "b", "c",],
            ],
            [
                ["a", "b", "c", "d", "e"],
                [2, -1],
                [0 => "c", 1 => "d",],
            ],
            [
                ["a", "b", "c", "d", "e"],
                [2, -1, true],
                [2 => "c",  3 => "d",],
            ],
            [
                [1 => "a", "b", "c", "d", "e"],
                [1, 2],
                [0 => "b", 1 => "c",],
            ],
            [
                ['a' => 'apple', 'b' => 'banana', '42' => 'pear', 'd' => 'orange'],
                [0, 3],
                ['a' => 'apple', 'b' => 'banana', 0 => 'pear'],
            ],
            [
                ['a' => 'apple', 'b' => 'banana', '42' => 'pear', 'd' => 'orange'],
                [0, 3, true],
                ['a' => 'apple', 'b' => 'banana', 42 => 'pear'],
            ],
        ];
    }
}
