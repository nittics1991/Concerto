<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class CountValuesFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'countValues';

    public function executeProvider()
    {
        return [
            [
                [1, "hello", 1, "world", "hello"],
                [],
                [1 => 2, "hello" => 2, "world" => 1,],
            ],
        ];
    }
}
