<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class UnshiftFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'unshift';

    public function executeProvider()
    {
        return [
            [
                ["orange", "banana"],
                [
                    "apple",
                    "raspberry"
                ],
                [
                    0 => 'apple',
                    1 => 'raspberry',
                    2 => 'orange',
                    3 => 'banana',
                ],
            ],
        ];
    }
}
