<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class KeysFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'keys';

    public function executeProvider()
    {
        return [
            [
                [0 => 100, "color" => "red"],
                [],
                [0, "color" ],
            ],
            [
                ["blue", "red", "green", "blue", "blue"],
                ["blue"],
                [0, 3, 4],
            ],
            [
                [
                    "color" => ["blue", "red", "green"],
                    "size"  => ["small", "medium", "large"],
                ],
                [],
                ['color', 'size'],
            ],
        ];
    }
}
