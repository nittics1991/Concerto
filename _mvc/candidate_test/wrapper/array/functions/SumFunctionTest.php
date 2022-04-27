<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class SumFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'sum';

    public function executeProvider()
    {
        return [
            [
                [2, 4, 6, 8],
                [],
                20,
            ],
            [
                ["a" => 1.2, "b" => 2.3, "c" => 3.4],
                [],
                6.9,
            ],
        ];
    }
}
