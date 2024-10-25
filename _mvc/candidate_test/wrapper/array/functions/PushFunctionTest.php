<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
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
