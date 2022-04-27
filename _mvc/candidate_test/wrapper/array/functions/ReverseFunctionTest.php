<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ReverseFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'reverse';

    public function executeProvider()
    {
        return [
            [
                ["php", 4.0, ["green", "red"]],
                [],
                [0 => ["green", "red"], 1 => 4.0, 2 => "php",]
            ],
            [
                ["php", 4.0, ["green", "red"]],
                [true],
                [2 => ["green", "red"], 1 => 4.0, 0 => "php",]
            ],
        ];
    }
}
