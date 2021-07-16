<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
