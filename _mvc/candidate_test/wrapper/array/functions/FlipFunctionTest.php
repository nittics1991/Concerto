<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class FlipFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'flip';

    public function executeProvider()
    {
        return [
            [
                ["oranges", "apples", "pears"],
                [],
                ['oranges' => 0,'apples' => 1,'pears' => 2,],
            ],
            [
                ["a" => 1, "b" => 1, "c" => 2],
                [],
                [1 => "b", 2 => "c",],
            ],
        ];
    }
}
