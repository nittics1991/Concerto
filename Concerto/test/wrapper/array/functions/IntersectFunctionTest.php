<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
