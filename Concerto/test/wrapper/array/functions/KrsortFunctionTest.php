<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
