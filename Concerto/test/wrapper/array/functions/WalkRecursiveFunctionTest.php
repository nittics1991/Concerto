<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class WalkRecursiveFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'walkRecursive';

    public function executeProvider()
    {
        return [
            [
                [
                    'sewwt' => ['a' => 'apple', 'b' => 'banana'],
                    'sour' => 'lemon'
                ],
                [
                    function(&$val, $key) {
                        $val = "change_{$val}";
                    }
                ],
                [
                    'sewwt' => ['a' => 'change_apple', 'b' => 'change_banana'],
                    'sour' => 'change_lemon'
                ],
            ],
        ];
    }
}
