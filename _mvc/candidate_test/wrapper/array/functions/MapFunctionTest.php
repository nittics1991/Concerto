<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class MapFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'map';

    public function executeProvider()
    {
        return [
            [
                [1, 2, 3, 4, 5],
                [
                    function ($n) {
                        return ($n * $n * $n);
                    },
                ],
                [0 => 1,1 => 8,2 => 27,3 => 64,4 => 125,],
            ],
            [
                [1, 2, 3, 4, 5],
                [
                    function ($n, $m) {
                        return "The number {$n} is called {$m} in Spanish";
                    },
                    ['uno', 'dos', 'tres', 'cuatro', 'cinco'],
                ],
                [
                    0 => 'The number 1 is called uno in Spanish',
                    1 => 'The number 2 is called dos in Spanish',
                    2 => 'The number 3 is called tres in Spanish',
                    3 => 'The number 4 is called cuatro in Spanish',
                    4 => 'The number 5 is called cinco in Spanish',
                ],
            ],
            [
                [1, 2, 3, 4, 5],
                [
                    function ($n, $m) {
                         return [$n => $m];
                    },
                    ['uno', 'dos', 'tres', 'cuatro', 'cinco'],
                ],
                //注意
                // [
                    [
                        0 => [1 => 'uno',],
                        1 => [2 => 'dos',],
                        2 => [3 => 'tres',],
                        3 => [4 => 'cuatro',],
                        4 => [5 => 'cinco',],
                    ],
                // ],
                [
                    [1, 2, 3, 4, 5],
                    [
                        null,
                        ['one', 'two', 'three', 'four', 'five'],
                        ['uno', 'dos', 'tres', 'cuatro', 'cinco'],
                    ],
                    [
                        0 => [0 => 1,1 => 'one',2 => 'uno',],
                        1 => [0 => 2,1 => 'two',2 => 'dos',],
                        2 => [0 => 3,1 => 'three',2 => 'tres',],
                        3 => [0 => 4,1 => 'four',2 => 'cuatro',],
                        4 => [0 => 5,1 => 'five',2 => 'cinco',],
                    ],
                ],
                [
                    [1, 2, 3],
                    [null],
                    [0 => 1,1 => 2,2 => 3,],
                ],
            ],
        ];
    }
}
