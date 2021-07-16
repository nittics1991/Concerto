<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class PushFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'push';

    public function executeProvider()
    {
        return [
            [
                ["orange", "banana"],
                ["apple", "raspberry"],
                ["orange", "banana","apple", "raspberry",],
            ],
        ];
    }
}
