<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class CountFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'count';

    public function executeProvider()
    {
        return [
            [
                [1, "hello", 1, "world", "hello"],
                [],
                5,
            ],
        ];
    }
}
