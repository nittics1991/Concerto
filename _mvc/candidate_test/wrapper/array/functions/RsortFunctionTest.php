<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class RsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'rsort';

    public function executeProvider()
    {
        return [
            [
                ["lemon", "orange", "banana", "apple",],
                [],
                ["orange", "lemon", "banana", "apple", ],
            ],
        ];
    }
}
