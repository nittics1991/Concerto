<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class KrsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'krsort';

    public function executeProvider()
    {
        return [
            [
                ["d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple",],
                [],
                ["d" => "lemon", "c" => "apple", "b" => "banana", "a" => "orange",],
            ],
        ];
    }
}