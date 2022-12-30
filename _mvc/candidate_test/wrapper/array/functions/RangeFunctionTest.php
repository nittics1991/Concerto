<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class RangeFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'range';

    public function executeProvider()
    {
        return [
            [
                [],
                [1, 10, 1],
                range(1, 10, 1),
            ],
            [
                range(1, 10, 1),
                ['a', 'z'],
                range('a', 'z'),
            ],
        ];
    }
}