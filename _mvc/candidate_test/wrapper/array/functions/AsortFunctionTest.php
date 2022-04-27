<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class AsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'asort';

    public function executeProvider()
    {
        return [
            [
                ["d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple",],
                [],
                ["c" => "apple", "b" => "banana", "d" => "lemon", "a" => "orange",],
            ],
        ];
    }
}
