<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class IntersectFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'intersect';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "red", "blue"],
                [["b" => "green", "yellow", "red"]],
                ["a" => "green", 0 => "red"],
            ],
        ];
    }
}
