<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ArsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'arsort';

    public function executeProvider()
    {
        return [
            [
                ["d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple"],
                [],
                ["a" => "orange", "d" => "lemon", "b" => "banana", "c" => "apple"],
            ],
        ];
    }
}
