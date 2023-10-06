<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
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
