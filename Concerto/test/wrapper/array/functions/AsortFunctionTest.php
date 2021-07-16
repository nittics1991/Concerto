<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
