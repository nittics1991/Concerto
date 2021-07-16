<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
                    function(&$val, $key) {
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
