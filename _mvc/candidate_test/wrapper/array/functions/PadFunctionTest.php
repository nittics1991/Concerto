<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class PadFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'pad';

    public function executeProvider()
    {
        return [
            [
                [12, 10, 9],
                [5, 0],
                [12, 10, 9, 0, 0],
            ],
            [
                [12, 10, 9],
                [-7, -1],
                [-1, -1, -1, -1, 12, 10, 9],
            ],
            [
                [12, 10, 9],
                [2, 0],
                [12, 10, 9],
            ],
            [
                ['12', '10', '9'],
                [5, 'a',],
                ['12', '10', '9', 'a', 'a',],
            ],
        ];
    }
}
