<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class SortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'sort';

    public function executeProvider()
    {
        return [
            [
                ["lemon", "orange", "banana", "apple",],
                [],
                ["apple", "banana", "lemon", "orange",],
            ],
        ];
    }
}
