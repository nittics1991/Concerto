<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
