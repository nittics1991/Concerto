<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class KeysFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'keys';

    public function executeProvider()
    {
        return [
            [
                [0 => 100, "color" => "red"],
                [],
                [0, "color" ],
            ],
            [
                ["blue", "red", "green", "blue", "blue"],
                ["blue"],
                [0, 3, 4],
            ],
            [
                [
                    "color" => ["blue", "red", "green"],
                    "size"  => ["small", "medium", "large"],
               ],
                [],
                ['color', 'size'],
            ],
        ];
    }
}
