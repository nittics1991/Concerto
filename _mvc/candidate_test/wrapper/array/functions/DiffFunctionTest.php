<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class DiffFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'diff';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "red", "blue", "red"],
                [["b" => "green", "yellow", "red"]],
                [1 => 'blue',],
            ],
        ];
    }
}
