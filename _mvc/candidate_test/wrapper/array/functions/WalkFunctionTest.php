<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class WalkFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'walk';

    public function executeProvider()
    {
        return [
            [
                ['a' => 'apple', 'b' => 'banana', 'sour' => 'lemon'],
                [
                    function (&$val, $key) {
                        $val = "change_{$val}";
                    }
                ],
                [
                    'a' => 'change_apple',
                    'b' => 'change_banana',
                    'sour' => 'change_lemon',
                ],
            ],
        ];
    }
}
