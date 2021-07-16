<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
