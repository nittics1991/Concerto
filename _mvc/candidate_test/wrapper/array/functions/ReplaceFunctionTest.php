<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ReplaceFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'replace';

    public function executeProvider()
    {
        return [
            [
                ["orange", "banana", "apple", "raspberry"],
                [
                    [0 => "pineapple", 4 => "cherry"],
                    [0 => "grape"],
                ],
                [0 => 'grape',1 => 'banana',2 => 'apple',3 => 'raspberry',4 => 'cherry',]
            ],
        ];
    }
}
