<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class KsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'ksort';

    public function executeProvider()
    {
        return [
            [
                ["d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple",],
                [],
                ["a" => "orange", "b" => "banana", "c" => "apple", "d" => "lemon",],
            ],
        ];
    }
}
